<?php
/**
 * Class caching
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class caching{
    private $hdl;
    private $memobj = false;
    public function __construct(){
        $this->hdl = database::getInstance();
        if (IS_CACHING && CACHING_TYPE == 1){
            $this->memobj = new Memcache;
            $this->memobj->connect('localhost', 11211) or $this->memobj = false;
//        $this->memobj->close();
        } else {
            $this->memobj = false;
        }
    }

    public function getCahedTemplate($key=''){
        if (empty($key)){
            return false;
        }
        switch(CACHING_TYPE){
            case 0: // DB
                $template = $this->hdl->selectElem(DB_T_PREFIX."cache",
                    "   template",
                    "   `key` LIKE '".$key."'
                ORDER BY datetime_add DESC
                LIMIT 1");
                $template = isset($template[0]['template']) ? $template[0]['template'] : null;
                break;
            case 1: // Memcached
                $template = @$this->memobj->get($key);
                break;
        }

        return $template;
    }

    public function setCachedTemplate($key='', $template='index.tpl'){
        if (empty($key)){
            return false;
        }
        switch(CACHING_TYPE){
            case 0: // DB
                $elem = array(
                    $key,
                    $template,
                    'NOW()'
                );
                $ret = ($this->hdl->addElem(DB_T_PREFIX."cache", $elem)) ? true : false;
                return $ret;
                break;
            case 1: // Memcached
                $this->memobj->set($key, $template, false, CACHING_LIFETIME);
                break;
        }
         return true;
    }

    public function getRequestKey(){
        $q_sting = $_SERVER["REQUEST_URI"].$_SERVER['QUERY_STRING'];
        if (empty($q_sting)){
            $q_sting = '/index.php';
        }
        $q_sting = $_SERVER["HTTP_HOST"].$q_sting;
//        echo "<!-- pre>";
//        var_dump($q_sting)."\n\n";
//        var_dump(md5($q_sting));
//        echo "</pre -->";
        return md5($q_sting);

//        $request = $_REQUEST;
//        echo "<!-- pre>";
//        var_dump($_SERVER["QUERY_STRING"]);
//        echo "</pre -->";
//        unset($request[session_name()]);
//        return md5(serialize($request));//.((!empty($_SESSION))?serialize($_SESSION):''));
    }

    public function clearCache(){
        switch(CACHING_TYPE){
            case 0: // 0 - clear DB cache
                $this->hdl->clearCacheTable();
                break;
            case 1: // 1 - clear memcache cache
                if (empty($this->memobj)){
                    $this->memobj = new Memcache;
                    $this->memobj->connect('localhost', 11211) or $this->memobj = false;
                }
                $this->memobj->flush();
                break;
        }
        if($cache_dir = opendir(__DIR__.'/../'.CACHE_DIR)) {
            while(false !== ($cache_file = readdir($cache_dir))) {
                if ($cache_file != "." && $cache_file != "..") {
                    unlink(__DIR__ . '/../' . CACHE_DIR . '/' . $cache_file);
                }
            }
            closedir($cache_dir);
        }
        if($compile_dir = opendir(__DIR__.'/../'.COMPILE_DIR)) {
            while(false !== ($compile_file = readdir($compile_dir))) {
                if ($compile_file != "." && $compile_file != "..") {
                    unlink(__DIR__ . '/../' . COMPILE_DIR . $compile_file);
                }
            }
            closedir($compile_dir);
        }
    }
}
