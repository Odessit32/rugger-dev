<?php
    /*
        This api recieves command name and other parameters
        and returns respond in json
    */

    // Use environment variables or fallback to old production credentials
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'kredoo3g_rugger';
    $db_pass = getenv('DB_PASS') ?: 'F8CPAkx%';
    $db_name = getenv('DB_NAME') ?: 'kredoo3g_rugger';

    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if (!$link) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Database connection failed',
            'message' => mysqli_connect_error()
        ]);
        die();
    }

    // Set charset
    mysqli_set_charset($link, 'utf8');

    $post = array();
    $post = json_decode(file_get_contents('php://input'), true);

    if (isset($post['command'])) {
        $command = $post['command'];
    }

    if (isset($post['tab'])) {
        $tab = $post['tab'];
    }
    if (isset($post['id'])) {
        $id = $post['id'];
    }

    if (isset($post['newSettingsData'])) {
        $newSettingsData = $post['newSettingsData'];
    }
    if (isset($post['formData'])) {
        $formData = $post['formData'];
    }

    if ($command == 'getChampionshipList') {
        getChampionshipList($link);
        die();
    } else if ($command == 'getDbPageSettings') {
        getDbPageSettings($link, $tab);
        die();
    } else if ($command == 'saveDbPageSettings') {
        saveDbPageSettings($link, $tab, $newSettingsData);
        die();
    } else if ($command == 'newDbPageObject') {
        newDbPageObject($link, $formData);
        die();
    } else if ($command == 'getDbObjectsData') {
        getDbObjectsData($link);
        die();
    } else if ($command == 'dbPageObjectsCardGetProfileInfo') {
        dbPageObjectsCardGetProfileInfo($link, $id);
        die();
    } else if ($command == 'dbPageTeamsCardGetProfileInfo') {
        dbPageTeamsCardGetProfileInfo($link, $id);
        die();
    } else if ($command == 'saveDbPageObjectCard') {
        saveDbPageObjectCard($link, $formData, $id);
        die();
    } else if ($command == 'saveDbPageTeamCard') {
        saveDbPageTeamCard($link, $formData, $id);
        die();
    } 
    else if ($command == 'getDbTeamsData') {
        getDbTeamsData($link);
        die();
    }

    function saveDbPageObjectCard($link, $formData, $id) {
        // сохранить редактированную карту стадиона на странице баз данных
        $name = $formData['name'];
        $city = $formData['city'];
        $adress = $formData['adress'];
        $map = $formData['map'];
        $linkPoint = $formData['link'];
        $description = $formData['description'];

        $query_text = "
            UPDATE database_page_objects 
            SET 
                name = '$name', 
                city = '$city',
                adress = '$adress',
                map = '$map',
                link = '$linkPoint',
                description = '$description'
            WHERE id = '$id'
        ";
        echo $query_text;
        $query = mysqli_query($link, $query_text);
        /* while ($row_arr = mysqli_fetch_assoc($query)) {
            echo json_encode($row_arr);
        } */
    }

    function saveDbPageTeamCard($link, $formData, $id) {
        // сохранить редактированную карту команды на странице баз данных

        $isFirstRow = true;
        $newValues = "";
        foreach ($formData as $key => $value) {
            $newValueStr = " $key = '$value' ";

            if ($isFirstRow == false) {
                $newValueStr = " , " .$newValueStr;
            }

            $newValues .= $newValueStr;

            $isFirstRow = false;
        }
        // echo $newValues;
        $query_text = "
            UPDATE database_page_teams 
            SET 
                $newValues
            WHERE id = '$id'
        ";
        // echo $query_text;
        $query = mysqli_query($link, $query_text);
        
    }

    function dbPageObjectsCardGetProfileInfo($link, $id) {
        // получить всю информацию о стадионе из бд

        $query_text = "
            SELECT * 
            FROM database_page_objects
            WHERE id = '$id'
        ";
        $query = mysqli_query($link, $query_text);
        while ($row_arr = mysqli_fetch_assoc($query)) {
            echo json_encode($row_arr);
        }
    }

    function dbPageTeamsCardGetProfileInfo($link, $id) {

        $query_text = "
            SELECT * 
            FROM database_page_teams
            WHERE id = '$id'
        ";
        $query = mysqli_query($link, $query_text);
        while ($row_arr = mysqli_fetch_assoc($query)) {
            echo json_encode($row_arr);
        }
    }

    function getDbObjectsData($link) {
        $dbObjectsData = array();
        $query_text = "
            SELECT * FROM database_page_objects ORDER BY id ASC
        ";
        // echo $query_text;
        $query = mysqli_query($link, $query_text);
        while ($row_arr = mysqli_fetch_assoc($query)) {
            array_push($dbObjectsData, $row_arr);
        }
        echo json_encode($dbObjectsData);
    }

    function getDbTeamsData($link) {
        $dbTeamsData = array();
        $query_text = "
            SELECT * FROM database_page_teams ORDER BY id ASC
        ";
        // echo $query_text;
        $query = mysqli_query($link, $query_text);
        while ($row_arr = mysqli_fetch_assoc($query)) {
            array_push($dbTeamsData, $row_arr);
        }
        echo json_encode($dbTeamsData);
    }

    function newDbPageObject($link, $formData) {
        $valuesForInsert = '';
        $isFirstRow = true;
        $keys = '(';
        $values = '(';
        foreach ($formData as $key => $value) {
            
            if ($isFirstRow == false) {
                $keys .= ", `$key`";
                $values .= ", '$value'";
            } else {
                $keys .= "`$key`";
                $values .= "'$value'";
            }

            $isFirstRow = false;
        }
        $keys .= ')';
        $values .= ')';

        $query_text = "
            INSERT 
                INTO database_page_objects $keys
                VALUES $values
        ";
        // echo $query_text;
        $query = mysqli_query($link, $query_text);
    }

    function getChampionshipList($link) {
        /* 
            This function returns championship list in json
        */

        $query_text = "
            select 
                rgr_championship.ch_id,
                rgr_championship.ch_title_ru, 
                rgr_championship_group.chg_title_ru
            from 
                rgr_championship, rgr_championship_group
            where 
                rgr_championship_group.chg_id = rgr_championship.ch_chg_id
                and 
                rgr_championship.ch_is_done = 'no'
        ";
        $query = mysqli_query($link, $query_text);
        $array = array();
        while ($row_arr = mysqli_fetch_assoc($query)) {
            array_push($array, $row_arr);
        }
        echo json_encode($array);
    }

    function saveDbPageSettings($link, $tab, $newSettingsData) {
        $lastVersion = getLastVersionOfSettings($link, $tab);
        $newVersion = $lastVersion + 1;
        $valuesForInsert = '';
        $isFirstRow = true;
        foreach ($newSettingsData as $key => $value) {

            foreach ($value as $key2 => $value2) {
                $values = "( '$newVersion', '$tab', '$key', '$value2' )";
                if ($isFirstRow == false) {
                    $values = ',' .$values;
                }
                $valuesForInsert .= $values;
                $isFirstRow = false;
            }
        }
        $query_text = "
            INSERT 
                INTO database_page_settings (`version`, `tab`, `point`, `value`)
                VALUES {$valuesForInsert}
        ";
        // echo $query_text;
        $query = mysqli_query($link, $query_text);
    }

    function getDbPageSettings($link, $tab) {
        /* 
            This function returns settings tab data for database page
        */

        $lastVersion = getLastVersionOfSettings($link, $tab);

        if ($lastVersion == -1) {
            // error
        }

        $query_text = "
            select *
            from database_page_settings
            where 
                tab = '$tab'
                and version = '$lastVersion'
            order by id asc
        ";
        $query = mysqli_query($link, $query_text);
        $commonArray = array();
        while ($row_arr = mysqli_fetch_assoc($query)) {
            if (!isset($commonArray[$row_arr['point']])) {
                $commonArray[$row_arr['point']] = array();
            }
            array_push($commonArray[$row_arr['point']], $row_arr['value']);
        }

        echo json_encode($commonArray);
    }

    function getLastVersionOfSettings($link, $tab) {
        /* 
            Fuction returns last version of settings for selected tab
            ($tab argument) or -1 if something wrong
        */

        $query_text = "
            select tab, version
            from database_page_settings
            where tab = '$tab'
            order by version desc
            limit 1
        ";
        $query = mysqli_query($link, $query_text);
        $lastVersion = -1;
        while ($row_arr = mysqli_fetch_assoc($query)) {
            $lastVersion = $row_arr['version'];
        }
        return($lastVersion);
    }
?>