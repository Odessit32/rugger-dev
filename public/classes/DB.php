<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class database {
    private static $instance = null;
    private static $reused = false;

   public static function getInstance() {
    static $sharedInstance = null;
    if (!$sharedInstance) {
        $sharedInstance = new self();
    } else {
        self::$reused = true;
    }
    return $sharedInstance;
}

    private $dsn;
    private $hdl;
    private $memobj;

    function __construct() {
        global $host, $user, $pass, $name;
        if (self::$instance !== null) {
            return;
        }

        $this->connect();
        if (!empty(IS_CACHING_QUERY)) {
            $this->memobj = $this->initCacheConnection();
        } else {
            $this->memobj = false;
        }
    }

    /**
     * Инициализация кеш-соединения (Redis или Memcached)
     */
    private function initCacheConnection() {
        // Пробуем Redis (предпочтительнее)
        if (class_exists('Redis')) {
            try {
                $redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'localhost';
                if (@$redis->connect($host, 6379, 2.0)) {
                    $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
                    return ['type' => 'redis', 'obj' => $redis];
                }
            } catch (Exception $e) {
                error_log("Redis connection failed: " . $e->getMessage());
            }
        }

        // Fallback на Memcached (новый API)
        if (class_exists('Memcached')) {
            try {
                $memcached = new Memcached();
                $host = getenv('MEMCACHE_HOST') ?: 'localhost';
                $memcached->addServer($host, 11211);
                if ($memcached->getStats()) {
                    return ['type' => 'memcached', 'obj' => $memcached];
                }
            } catch (Exception $e) {
                error_log("Memcached connection failed: " . $e->getMessage());
            }
        }

        // Fallback на старый Memcache
        if (class_exists('Memcache')) {
            $memcache = new Memcache;
            $host = getenv('MEMCACHE_HOST') ?: 'localhost';
            if (@$memcache->connect($host, 11211)) {
                return ['type' => 'memcache', 'obj' => $memcache];
            }
        }

        return false;
    }

    public function connect() {
        global $db_connection;
        if (!empty($db_connection) && is_object($db_connection) && get_class($db_connection) == 'mysqli') {
            $this->hdl = $db_connection;
            return true;
        }
        global $total_db_query;
        if (empty($total_db_query['connections'])) {
            $total_db_query['connections'] = 0;
        }
        if (empty($this->hdl)) {
            $total_db_query['connections']++;
            $this->hdl = mysqli_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
            if (mysqli_connect_errno()) {
                // Логируем ошибку и возвращаем false
                error_log("Ошибка подключения к базе данных: " . mysqli_connect_error());
                $this->hdl = false; // Убедимся, что $this->hdl не содержит некорректного значения
                return false;
            } else {
                $this->setTimeZone(date('P'));
                mysqli_query($this->hdl, "SET NAMES utf8mb4");
                mysqli_query($this->hdl, "SET CHARACTER SET utf8mb4");
                mysqli_query($this->hdl, "SET character_set_connection = utf8mb4");
                mysqli_query($this->hdl, "SET collation_connection = utf8mb4_unicode_ci");
                return true;
            }
        }
    }

    public function delElem($table, $condition, $echo = false) {
        global $total_db_query; // logging
        $total_db_query['count']++;
        $query = "DELETE FROM $table WHERE $condition";
        $total_db_query['data_delete'][] = $query; // logging
        if ($echo) echo $query;

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        if ($res) {
            return true;
        }
        return false;
    }

    public function addElem($table, $fields, $echo = false) {
        global $total_db_query; // logging
        if (!empty($total_db_query['count'])) {
            $total_db_query['count']++;
        } else {
            $total_db_query['count'] = 1;
        }

        $query = '';
        $add_columns_name_c = 0;
        $add_columns_name = array();
        $i = 0;
        foreach ($fields as $key => $field) {
            $add_columns_name[] = $key;
            if ($i == $key) {
                $add_columns_name_c++;
            }
            $i++;
        }
        $q_columns_name = '';
        if ($add_columns_name_c !== count($fields) && !empty($add_columns_name)) {
            $q_columns_name = ' ( ';
            // Проверяем, что соединение установлено
            if ($this->hdl === false) {
                error_log("Попытка выполнить запрос без активного соединения с базой данных: SHOW KEYS FROM $table");
                return false;
            }
            $promary_key_res = mysqli_query($this->hdl, "SHOW KEYS FROM " . $table . " WHERE Key_name = 'PRIMARY'");
            if (!empty($promary_key_res)) {
                $promary_key = mysqli_fetch_array($promary_key_res, MYSQLI_ASSOC);
                if (!empty($promary_key['Column_name'])) {
                    $q_columns_name .= "`" . $promary_key['Column_name'] . "`, ";
                }
            }
            $q_columns_name .= '`' . implode('`, `', $add_columns_name) . '`) ';
        }
        $query .= "INSERT INTO $table " . $q_columns_name . " VALUES( NULL";
        foreach ($fields as $field) {
            if ($field === NULL) $query .= ", NULL";
            elseif ($field == 'NOW()' or $field == 'now()' or $field == 'Now()') $query .= ", $field";
            else $query .= ", '$field'";
        }
        $query .= ")";
        $total_db_query['data_incert'][] = $query; // logging
        if ($echo) {
            echo "<pre>";
            var_dump($query);
            echo "</pre>";
        }

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        // Debug logging disabled for production
        // error_log("[DEBUG DB.php] SQL запрос: " . $query, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");

        $result = mysqli_query($this->hdl, $query);

        if ($result) {
            $temp = mysqli_insert_id($this->hdl);
            // error_log("[DEBUG DB.php] INSERT успешен, insert_id: " . $temp, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return $temp;
        } else {
            // Debug logging disabled for production
            // $error = mysqli_error($this->hdl);
            // $errno = mysqli_errno($this->hdl);
            // error_log("[ERROR DB.php] INSERT провалился. Ошибка #" . $errno . ": " . $error, 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
            return false;
        }
    }

    public function selectElem($table, $what, $where, $echo = false, $cache = true, $cache_time = 300) {
    $__start = microtime(true);
    $__t = microtime(true);
        global $admin_user;
        $data = false;
        $ret_data = [];
        if (empty($cache_time) || $cache_time < 0) {
            $cache_time = 0;
            $cache = false;
        }
        $key = '';
        if (!empty(IS_CACHING_QUERY) && !empty($this->memobj) && $cache &&
            (empty($admin_user) || (empty($admin_user['admin_status']) && empty($admin_user['publisher_status'])))) {
            $key = $this->cacheGetKey(array($table, $what, $where));
            $data = $this->cacheGetData($key);
            if (!empty($data)) {
                if ($data == 'false') {
                    $data = false;
                }
                return $data;
            }
        }
        global $total_db_query; // logging
        if (empty($total_db_query['count'])) {
            $total_db_query['count'] = 0;
        }
        if (empty($total_db_query['data_select'])) {
            $total_db_query['data_select'] = array();
        }
        $total_db_query['count']++;
        $query = "SELECT $what FROM $table WHERE $where";
        //file_put_contents(__DIR__ . '/sql_queries.log', date('Y-m-d H:i:s') . " Query: $query\n", FILE_APPEND);        if ($echo) echo '<!-- ' . $query . "<br /><br /><br /> -->"; (логгирование времени загрузки)
        $time_start_ = $this->microtime_float();

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        $time_end_ = $this->microtime_float();
        $time = $time_end_ - $time_start_;
        $total_db_query['data_select'][] = $query . "; -- [data: " . $data . " ] [key: " . $key . "] [ time: " . $time . " ]"; // logging
        if (!empty($res)) {
            if (mysqli_num_rows($res) > 0) {
                while ($temp = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                    $ret_data[] = $temp;
                }
            }
        }
        if (!empty(IS_CACHING_QUERY) && !empty($this->memobj) && $cache &&
            (empty($admin_user) || (empty($admin_user['admin_status']) && empty($admin_user['publisher_status'])))) {
            if (empty($ret_data)) {
                $this->cacheSaveData($key, 'false', $cache_time);
            } else {
                $this->cacheSaveData($key, $ret_data, $cache_time);
            }
        }
        return empty($ret_data) ? false : $ret_data;
    }

    public function updateElem($table, $elems, $condition, $echo = false) {
        global $total_db_query; // logging
        if (empty($total_db_query['count'])) {
            $total_db_query['count'] = 0;
        }
        $total_db_query['count']++;
        if (sizeof($condition) > 0) {
            $query = "UPDATE $table SET ";
            foreach ($elems as $key => $val) {
                if ($val === NULL) $query .= "`$key` = NULL, ";
                elseif ($val == 'NOW()' or $val == 'now()' or $val == 'Now()') $query .= "`$key` = $val, ";
                else $query .= "`$key` = '$val', ";
            }
            $query = substr($query, 0, -2);
            $query .= " WHERE";
            foreach ($condition as $key => $val)
                $query .= " `$key` = '$val' and";
            $query = substr($query, 0, -4);
            $total_db_query['data_update'][] = $query; // logging
            if ($echo) echo $query . "<br /><br /><br /><br />";

            // Проверяем, что соединение установлено
            if ($this->hdl === false) {
                error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
                return false;
            }

            $res = mysqli_query($this->hdl, $query);
            if (!$res) {
                error_log("MySQL UPDATE Error: " . mysqli_error($this->hdl) . " Query: " . substr($query, 0, 500));
                return false;
            }
            return true;
        }
        return false;
    }

    public function updateAll($table, $elems) {
        global $total_db_query; // logging
        $total_db_query['count']++;
        if (sizeof($elems) > 0) {
            $query = "UPDATE $table SET ";
            foreach ($elems as $key => $val) {
                if ($val == 'NOW()' or $val == 'now()' or $val == 'Now()') $query .= "$key = $val, ";
                else $query .= "$key = '$val', ";
            }
            $query = substr($query, 0, -2);
            $total_db_query['data_update'][] = $query; // logging

            // Проверяем, что соединение установлено
            if ($this->hdl === false) {
                error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
                return false;
            }

            $res = mysqli_query($this->hdl, $query);
            return true;
        }
        return false;
    }

    public function updateElemExtra($table, $elems, $extra) {
        global $total_db_query; // logging
        $total_db_query['count']++;
        if (sizeof($elems) > 0) {
            $query = "UPDATE $table SET ";
            foreach ($elems as $key => $val) {
                if ($val == 'NOW()' or $val == 'now()' or $val == 'Now()') $query .= "$key = $val, ";
                else $query .= "$key = '$val', ";
            }
            $query = substr($query, 0, -2);
            $query .= " WHERE " . $extra;
            $total_db_query['data_update'][] = $query; // logging

            // Проверяем, что соединение установлено
            if ($this->hdl === false) {
                error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
                return false;
            }

            $res = mysqli_query($this->hdl, $query);
            return true;
        }
    }

    public function getTables() {
        global $total_db_query; // logging
        $list = array();
        $total_db_query['count']++;
        $query = "SHOW TABLES";
        $total_db_query['data_other'][] = $query; // logging

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        if ($res) {
            if (mysqli_num_rows($res) == 0) return false;
            while ($temp = mysqli_fetch_array($res))
                $list[] = $temp;
            return $list;
        }
        return false;
    }

    public function showCreateTable($table = '') {
        global $total_db_query; // logging
        $total_db_query['count']++;
        if (trim($table) == '') return false;
        $search = array("\\"."'", "\\".'"', "'", '"', ' ');
        $replace = array('', '', '', '', '');
        $table = str_replace($search, $replace, $table);
        $query = "SHOW CREATE TABLE `$table`";
        $total_db_query['data_other'][] = $query; // logging

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        if ($res) {
            if (mysqli_num_rows($res) == 0) return false;
            $temp = mysqli_fetch_array($res);
            return $temp[1];
        } else return false;
    }

    public function showInsertTable($table = '', &$file = false) {
        global $total_db_query; // logging
        $total_db_query['count']++;
        $text = '';
        if (trim($table) == '') return false;
        $search = array("\\"."'", "\\".'"', "'", '"', ' ');
        $replace = array('', '', '', '', '');
        $table = str_replace($search, $replace, $table);
        $query = "SELECT * FROM `$table`";
        $total_db_query['data_other'][] = $query . "\n -- showInsertTable\n\n"; // logging

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        if ($res) {
            if (mysqli_num_rows($res) == 0) return false;
            while ($temp = mysqli_fetch_row($res)) {
                $text .= 'INSERT INTO ' . $table . ' VALUES (';
                $values = '';
                foreach ($temp as $item) {
                    if (strpos(' ' . $item, '\\\'') > 0 or strpos(' ' . $item, '\\"') > 0) while (strpos(' ' . $item, '\\\'') > 0 or strpos(' ' . $item, '\\"') > 0) $item = stripslashes($item);
                    $values .= '\'' . addslashes($item) . '\', ';
                }
                $text .= substr($values, 0, -2);
                $text .= ');' . "\r\n";
                if (!empty($file)) {
                    fwrite($file, $text);
                    $text = '';
                }
            }
            if (empty($file)) {
                return $text;
            }
            return true;
        }
        return false;
    }

    public function clearCacheTable() {
        global $total_db_query; // logging
        $total_db_query['count']++;
        $query = "TRUNCATE `" . DB_T_PREFIX . "cache`";
        $total_db_query['data_other'][] = $query; // logging

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: $query");
            return false;
        }

        $res = mysqli_query($this->hdl, $query);
        if ($res) {
            return true;
        }
        return false;
    }

    public function setTimeZone($TZ = '') {
        if (empty($TZ)) {
            $TZ = date('P');
        }

        // Проверяем, что соединение установлено
        if ($this->hdl === false) {
            error_log("Попытка выполнить запрос без активного соединения с базой данных: SET `time_zone`='$TZ'");
            return false;
        }

        $res = mysqli_query($this->hdl, "SET `time_zone`='" . $TZ . "'");
        if ($res) {
            return true;
        }
        return false;
    }

    public function disconnect() {
        global $total_db_query;
        if (empty($total_db_query['disconnections'])) {
            $total_db_query['disconnections'] = 0;
        }
        $total_db_query['disconnections']++;
        if (!empty($this->hdl) &&
            is_object($this->hdl) &&
            get_class($this->hdl) == 'mysqli') {
            @mysqli_close($this->hdl);
            $this->hdl = null; // Очищаем $this->hdl после закрытия
        }
    }

    public function __destruct() {
        $this->disconnect();
    }

    private function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    private function cacheGetKey($params = false) {
        if (empty($params)) {
            return false;
        }
        return md5(serialize($params));
    }

    private function cacheGetData($key = '') {
        if (empty($key) || empty($this->memobj)) {
            return false;
        }

        $cache = $this->memobj;

        switch ($cache['type']) {
            case 'redis':
                $data = @$cache['obj']->get($key);
                return $data !== false ? $data : false;

            case 'memcached':
                $data = @$cache['obj']->get($key);
                return $cache['obj']->getResultCode() === Memcached::RES_SUCCESS ? $data : false;

            case 'memcache':
                return @$cache['obj']->get($key);

            default:
                return false;
        }
    }

    private function cacheSaveData($key = '', $data = false, $time = false) {
        if (empty($key) || empty($this->memobj)) {
            return false;
        }
        if (empty($time) || $time < 0) {
            $time = CACHING_LIFETIME;
        }

        $cache = $this->memobj;

        switch ($cache['type']) {
            case 'redis':
                return @$cache['obj']->setex($key, $time, $data);

            case 'memcached':
                return @$cache['obj']->set($key, $data, $time);

            case 'memcache':
                return @$cache['obj']->set($key, $data, 0, $time);

            default:
                return false;
        }
    }

    /**
     * Очистка кеша по паттерну (только Redis)
     */
    public function cacheClear($pattern = '*') {
        if (empty($this->memobj)) {
            return false;
        }

        $cache = $this->memobj;

        if ($cache['type'] === 'redis') {
            $keys = $cache['obj']->keys($pattern);
            if (!empty($keys)) {
                return $cache['obj']->del($keys);
            }
        } elseif ($cache['type'] === 'memcached') {
            return $cache['obj']->flush();
        } elseif ($cache['type'] === 'memcache') {
            return $cache['obj']->flush();
        }

        return false;
    }

    /**
     * Получить информацию о кеше
     */
    public function getCacheInfo() {
        if (empty($this->memobj)) {
            return ['type' => 'none', 'available' => false];
        }

        $cache = $this->memobj;

        return [
            'type' => $cache['type'],
            'available' => true
        ];
    }
}