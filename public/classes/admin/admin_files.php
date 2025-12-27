<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class files{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getImagesList($folder = '', $page = 0, $perpage = 10){
        if ($folder != '') $extra_q = " AND f_folder = '$folder' ";
        else $extra_q = "";
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 1) $perpage = 10;
        $offset = $perpage * $page;
        return $this->hdl->selectElem(DB_T_PREFIX."files","*","f_type = 'images'".$extra_q." ORDER BY f_id DESC LIMIT $offset, $perpage");
    }

    public function getImagesPages($folder = '', $page = 0, $perpage = 10){
        if ($folder != '') $extra_q = " AND f_folder = '$folder' ";
        else $extra_q = "";
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 1) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."files","COUNT(*) as C_N","f_type = 'images'".$extra_q);
        $c_pages = intval(($temp[0]['C_N'] - 1) / $perpage);
        $pages = array();
        if ($c_pages <= 9){
            for ($i = 0; $i <= $c_pages; $i++){
                $pages[$i] = $i + 1;
            }
        } else {
            if ($page < 6){
                for ($i = 0; $i < 9 && $i <= $c_pages; $i++) $pages[$i] = $i + 1;
            } else {
                for ($i = $page - 5; $i < $page + 4 && $i <= $c_pages; $i++) $pages[$i] = $i + 1;
            }
        }
        return $pages;
    }

    public function getImageItem($f_id){
        $f_id = intval($f_id);
        if ($f_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_id = $f_id AND f_type = 'images' LIMIT 0, 1");
            return $item[0];
        } else return false;
    }

    public function saveImage(){
        $file = $_FILES['file_image'];
        if ($folder != '') $folder=addslashes(strip_tags($_POST['f_folder']));
        else $folder = '/';
        $about_rus = strip_tags($_POST['f_about_ru']);
        $about_ukr = strip_tags($_POST['f_about_ua']);
        $about_eng = strip_tags($_POST['f_about_en']);

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');

        if ($file['error'] != 0 or $file['size'] == 0) return false;
        $file['name'] = $this->getTranslit($file['name']);
        $file_name = $this->fileName('images', $folder, $file['name'], 0);
        if (move_uploaded_file($file['tmp_name'], "../upload/images".$folder.$file_name)) {
            $iData = array(
                $file_name,
                str_replace($search, $replace, $about_rus),
                str_replace($search, $replace, $about_ukr),
                str_replace($search, $replace, $about_eng),
                "NOW()",
                "NOW()",
                USER_ID,
                'images',
                $folder
            );
            if ($this->hdl->addElem(DB_T_PREFIX."files", $iData)) return true;
            else return false;
        }else return false;
    }

    public function saveEditedImage(){
        $f_id = intval($_POST['f_id']);
        if ($f_id<1) return false;
        $file = $_FILES['file_image'];
        if ($folder != '') $folder=addslashes(strip_tags($_POST['f_folder']));
        else $folder = '/';
        $about_rus = strip_tags($_POST['f_about_ru']);
        $about_ukr = strip_tags($_POST['f_about_ua']);
        $about_eng = strip_tags($_POST['f_about_en']);

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array();
        $iData['f_about_ru'] = str_replace($search, $replace, $about_rus);
        $iData['f_about_ua'] = str_replace($search, $replace, $about_ukr);
        $iData['f_about_en'] = str_replace($search, $replace, $about_eng);
        if ($file['error'] == 0 and $file['size'] > 0) {
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_id = '$f_id' AND f_type = 'images' LIMIT 0, 1");
            if (unlink("../upload/images".$old_image[0]['f_folder'].$old_image[0]['f_path'])){
                $file['name'] = $this->getTranslit($file['name']);
                $file_name = $this->fileName('images', $folder, $file['name'], 0);
                if (move_uploaded_file($file['tmp_name'], "../upload/images".$folder.$file_name)) {
                    $iData['f_path'] = $file_name;
                }
            }
        }
        $iData['f_folder'] = $folder;
        $iData['f_datetime_edit'] = 'NOW()';
        $iData['f_author'] = USER_ID;
        $condition = array(
            "f_id"=>$f_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."files",$iData, $condition)) return true;
        else return false;
    }

    public function fileName($type='files', $folder='/', $file_name, $corrector=0){
        $corrector = intval($corrector);
        if ($corrector>0){
            $p = strpos($file_name, '.');
            $new_file_name = substr($file_name, 0, $p)."_".$corrector.substr($file_name, $p);
        } else $new_file_name = $file_name;
        $dir = opendir ("../upload/".$type.$folder);
        $flag = 0;
        while ($file = readdir ($dir)) {
            if($file == $new_file_name) $flag = 1;
        }
        closedir ($dir);
        if ($flag == 1) {
            $corrector++;
            return $this->fileName($type, $folder, $file_name, $corrector);
        }else return $new_file_name;
    }

    public function deleteFile($f_id=0){
        $f_id = intval($f_id);
        if ($f_id>0){
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_id = '$f_id' LIMIT 0, 1");
            if ($old_image){
                $this->hdl->delElem(DB_T_PREFIX."attachments", "at_f_id = '$f_id'");
                if ($old_image[0]['f_folder'] == '') $old_image[0]['f_folder'] = '/';
                if (unlink("../upload/".$old_image[0]['f_type'].$old_image[0]['f_folder'].$old_image[0]['f_path'])){
                    if ($this->hdl->delElem(DB_T_PREFIX."files", "f_id = '$f_id' LIMIT 1")) return true;
                    else return false;
                }else return false;
            }else return false;
        }else return false;
    }

    public function getFilesList($folder = '', $page = 0, $perpage = 10){
        if ($folder != '') $extra_q = " AND f_folder = '$folder' ";
        else $extra_q = "";
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 1) $perpage = 10;
        $offset = $perpage * $page;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_type = 'files'".$extra_q." ORDER BY f_id DESC LIMIT $offset, $perpage");
        if ($temp){
            for ($i=0; $i<count($temp); $i++){
                $temp_item = $temp[$i]['f_path'];
                $temp_item = substr(strrchr($temp_item, "."), 1);
                $temp_item = strtolower ($temp_item);
                switch($temp_item) {
                    case "pdf":
                        $temp[$i]['ico'] = 'images/pdf.gif';
                        break;
                    case "doc":
                        $temp[$i]['ico'] = 'images/doc.gif';
                        break;
                    case "docx":
                        $temp[$i]['ico'] = 'images/doc.gif';
                        break;
                    case "xls":
                        $temp[$i]['ico'] = 'images/xls.gif';
                        break;
                    case "txt":
                        $temp[$i]['ico'] = 'images/txt.gif';
                        break;
                    default:
                        $temp[$i]['ico'] = 'images/file.gif';
                }
            }
            return $temp;
        } else return false;
    }

    public function getFilesPages($folder = '', $page = 0, $perpage = 10){
        if ($folder != '') $extra_q = " AND f_folder = '$folder' ";
        else $extra_q = "";
        $page = intval($page);
        $perpage = intval($perpage);
        if ($perpage < 1) $perpage = 10;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."files","COUNT(*) as C_N","f_type = 'files'".$extra_q);
        $c_pages = intval(($temp[0]['C_N'] - 1) / $perpage);
        $pages = array();
        if ($c_pages <= 9){
            for ($i = 0; $i <= $c_pages; $i++){
                $pages[$i] = $i + 1;
            }
        } else {
            if ($page < 6){
                for ($i = 0; $i < 9 && $i <= $c_pages; $i++) $pages[$i] = $i + 1;
            } else {
                for ($i = $page - 5; $i < $page + 4 && $i <= $c_pages; $i++) $pages[$i] = $i + 1;
            }
        }
        return $pages;
    }

    public function getFileItem($f_id){
        $f_id = intval($f_id);
        if ($f_id >0) {
            $item = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_id = $f_id AND f_type = 'files' LIMIT 0, 1");
            $temp_item = $item[0]['f_path'];
            $temp_item = substr(strrchr($temp_item, "."), 1);
            $temp_item = strtolower ($temp_item);
            switch($temp_item) {
                case "pdf":
                    $item[0]['ico'] = 'images/pdf.gif';
                    break;
                case "doc":
                    $item[0]['ico'] = 'images/doc.gif';
                    break;
                case "docx":
                    $item[0]['ico'] = 'images/doc.gif';
                    break;
                case "xls":
                    $item[0]['ico'] = 'images/xls.gif';
                    break;
                case "txt":
                    $item[0]['ico'] = 'images/txt.gif';
                    break;
                default:
                    $item[0]['ico'] = 'images/file.gif';
            }
            return $item[0];
        } else return false;
    }

    public function saveFile(){
        $file = $_FILES['file_image'];
        if ($folder != '') $folder=addslashes(strip_tags($_POST['f_folder']));
        else $folder = '/';
        $about_rus = strip_tags($_POST['f_about_ru']);
        $about_ukr = strip_tags($_POST['f_about_ua']);
        $about_eng = strip_tags($_POST['f_about_en']);

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');

        if ($file['error'] != 0 or $file['size'] == 0) return false;
        $file['name'] = $this->getTranslit($file['name']);
        $file_name = $this->fileName('files', $folder, $file['name'], 0);
        if (move_uploaded_file($file['tmp_name'], "../upload/files".$folder.$file_name)) {
            $iData = array(
                $file_name,
                str_replace($search, $replace, $about_rus),
                str_replace($search, $replace, $about_ukr),
                str_replace($search, $replace, $about_eng),
                "NOW()",
                "NOW()",
                USER_ID,
                'files',
                $folder
            );
            if ($this->hdl->addElem(DB_T_PREFIX."files", $iData)) return true;
            else return false;
        }else return false;
    }

    public function saveEditedFile(){
        $f_id = intval($_POST['f_id']);
        if ($f_id<1) return false;
        $file = $_FILES['file_image'];
        if ($folder != '') $folder=addslashes(strip_tags($_POST['f_folder']));
        else $folder = '/';
        $about_rus = strip_tags($_POST['f_about_ru']);
        $about_ukr = strip_tags($_POST['f_about_ua']);
        $about_eng = strip_tags($_POST['f_about_en']);

        $search = array("\\"."'", "\\".'"', "'", '"');
        $replace = array('', '', '', '');
        $iData = array();
        $iData['f_about_ru'] = str_replace($search, $replace, $about_rus);
        $iData['f_about_ua'] = str_replace($search, $replace, $about_ukr);
        $iData['f_about_en'] = str_replace($search, $replace, $about_eng);
        if ($file['error'] == 0 and $file['size'] > 0) {
            $old_image = $this->hdl->selectElem(DB_T_PREFIX."files","*","f_id = '$f_id' AND f_type = 'files' LIMIT 0, 1");
            if (unlink("../upload/files".$old_image[0]['f_folder'].$old_image[0]['f_path'])){
                $file['name'] = $this->getTranslit($file['name']);
                $file_name = $this->fileName('files', $folder, $file['name'], 0);
                if (move_uploaded_file($file['tmp_name'], "../upload/files".$folder.$file_name)) {
                    $iData['f_path'] = $file_name;
                }
            }
        }
        $iData['f_folder'] = $folder;
        $iData['f_datetime_edit'] = 'NOW()';
        $iData['f_author'] = USER_ID;
        $condition = array(
            "f_id"=>$f_id
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."files",$iData, $condition)) return true;
        else return false;
    }

    private function getTranslit($st, $charset = 'utf-8'){
        if (strtolower($charset) == 'utf-8'){
            $st = iconv("UTF-8", "cp1251", $st);
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, iconv("UTF-8", "cp1251", "абвгдеёзийклмнопрстуфхыэ"), "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, iconv("UTF-8", "cp1251", "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ"), "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    iconv("UTF-8", "cp1251", "ж")=>"zh",
                    iconv("UTF-8", "cp1251", "ц")=>"ts",
                    iconv("UTF-8", "cp1251", "ч")=>"ch",
                    iconv("UTF-8", "cp1251", "ш")=>"sh",
                    iconv("UTF-8", "cp1251", "щ")=>"shch",
                    iconv("UTF-8", "cp1251", "ь")=>"",
                    iconv("UTF-8", "cp1251", "ю")=>"yu",
                    iconv("UTF-8", "cp1251", "я")=>"ya",
                    iconv("UTF-8", "cp1251", "ъ")=>"",
                    iconv("UTF-8", "cp1251", "Ъ")=>"",
                    " "=>"_",
                    iconv("UTF-8", "cp1251", "Ж")=>"ZH",
                    iconv("UTF-8", "cp1251", "Ц")=>"TS",
                    iconv("UTF-8", "cp1251", "Ч")=>"CH",
                    iconv("UTF-8", "cp1251", "Ш")=>"SH",
                    iconv("UTF-8", "cp1251", "Щ")=>"SHCH",
                    iconv("UTF-8", "cp1251", "Ь")=>"",
                    iconv("UTF-8", "cp1251", "Ю")=>"YU",
                    iconv("UTF-8", "cp1251", "Я")=>"YA",
                    iconv("UTF-8", "cp1251", "ї")=>"i",
                    iconv("UTF-8", "cp1251", "Ї")=>"Yi",
                    iconv("UTF-8", "cp1251", "є")=>"ie",
                    iconv("UTF-8", "cp1251", "Є")=>"Ye",
                    iconv("UTF-8", "cp1251", "!")=>'',
                    iconv("UTF-8", "cp1251", "@")=>'',
                    iconv("UTF-8", "cp1251", "#")=>'',
                    iconv("UTF-8", "cp1251", "№")=>'',
                    iconv("UTF-8", "cp1251", "$")=>'',
                    iconv("UTF-8", "cp1251", "%")=>'',
                    iconv("UTF-8", "cp1251", "^")=>'',
                    iconv("UTF-8", "cp1251", "&")=>'',
                    iconv("UTF-8", "cp1251", "*")=>'',
                    iconv("UTF-8", "cp1251", "(")=>'',
                    iconv("UTF-8", "cp1251", ")")=>'',
                    iconv("UTF-8", "cp1251", "+")=>'',
                    iconv("UTF-8", "cp1251", "=")=>'',
                    iconv("UTF-8", "cp1251", "[")=>'',
                    iconv("UTF-8", "cp1251", "]")=>'',
                    iconv("UTF-8", "cp1251", "{")=>'',
                    iconv("UTF-8", "cp1251", "}")=>'',
                    iconv("UTF-8", "cp1251", "|")=>'',
                    iconv("UTF-8", "cp1251", "?")=>'',
                    iconv("UTF-8", "cp1251", ">")=>'',
                    iconv("UTF-8", "cp1251", "<")=>'',
                    iconv("UTF-8", "cp1251", "`")=>'',
                    iconv("UTF-8", "cp1251", "~")=>''
                )
            );
        } else {
            // Сначала заменяем "односимвольные" фонемы.
            $st=strtr($st, "абвгдеёзийклмнопрстуфхыэ", "abvgdeeziyklmnoprstufhie");
            $st=strtr($st, "АБВГДЕЁЗИЙКЛМНОПРСТУФХЫЭ", "ABVGDEEZIYKLMNOPRSTUFHIE");
            // Затем - "многосимвольные и др.".
            $st=strtr($st, array(
                    "ж"=>"zh",
                    "ц"=>"ts",
                    "ч"=>"ch",
                    "ш"=>"sh",
                    "щ"=>"shch",
                    "ь"=>"",
                    "ю"=>"yu",
                    "я"=>"ya",
                    "ъ"=>"",
                    "Ъ"=>"",
                    " "=>"_",
                    "Ж"=>"ZH",
                    "Ц"=>"TS",
                    "Ч"=>"CH",
                    "Ш"=>"SH",
                    "Щ"=>"SHCH",
                    "Ь"=>"",
                    "Ю"=>"YU",
                    "Я"=>"YA",
                    "ї"=>"i",
                    "Ї"=>"Yi",
                    "є"=>"ie",
                    "Є"=>"Ye",
                    "!"=>'',
                    "@"=>'',
                    "#"=>'',
                    "№"=>'',
                    "$"=>'',
                    "%"=>'',
                    "^"=>'',
                    "&"=>'',
                    "*"=>'',
                    "("=>'',
                    ")"=>'',
                    "+"=>'',
                    "="=>'',
                    "["=>'',
                    "]"=>'',
                    "{"=>'',
                    "}"=>'',
                    "|"=>'',
                    "?"=>'',
                    ">"=>'',
                    "<"=>'',
                    "`"=>'',
                    "~"=>''
                )
            );
        }
        // Возвращаем результат.
        return $st;
    }
}
?>
