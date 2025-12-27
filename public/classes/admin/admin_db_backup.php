<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class db_backup{
    private $hdl;
    public $dir_backup = "./bd_backup";

    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getBackupList(){
        $list = false;
        $dir = opendir ($this->dir_backup);
        while ( $file = readdir ($dir)) {
            if (( $file != ".") AND ($file != "..") AND ($file != "index.php") ) {
                $item['name'] = $file;
                $item['edit_time'] = filemtime($this->dir_backup."/".$file);
                $item['size_bites'] = filesize($this->dir_backup."/".$file);
                $item['size'] = str_replace('.', ',', $this->formatBytes($item['size_bites'], $precision = 2));
                $list[] = $item;
            }
        }
        closedir ($dir);
        return $list;
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function createBackup() {
        $fname = $this->dir_backup."/".date("Y-m-d_H-i-s").'.sql';
        $file = fopen ($fname,"w+");
        if ( !$file ) return false;
        $text .= '-- SQL Dump'."\r\n";
        $text .= '-- Date and time created: '.date("d.m.Y").', '.date("H:i:s")."\r\n";
        $text .= '-- Create by illi.od.ua'."\r\n\r\n\r\n\r\n";
        fwrite ( $file, $text );
        $tables = $this->hdl->getTables();
        if ($tables){
            foreach ($tables as $item){
                $text = 'DROP TABLE IF EXISTS `' . $item[0] . '`;';
                $text .= "\r\n\r\n";
                fwrite ( $file, $text );
                $text = $this->hdl->showCreateTable($item[0]);
                $text .= ";\r\n\r\n";
                fwrite ( $file, $text );

                if ($this->hdl->showInsertTable($item[0], $file)) $text = "\r\n\r\n\r\n";
                else $text = "\r\n\r\n";
                fwrite ( $file, $text );
            }
        }
        fclose ($file);
        if ( $this->getGzipFile ( $fname ) ) unlink ( $fname );
        if ( file_exists ($fname.'.gz') ) return true;
        else return false;
    }

    private function getGzipFile($source, $dest='', $level=9) {
        if ( $dest == '' ) $dest = $source.'.gz';

        $mode = 'wb'.$level;
        $error=false;

        if( $file_out = gzopen ( $dest, $mode ) ) {
            if( $file_in = fopen( $source, 'rb' ) ) {
                while( !feof ( $file_in ) ) gzwrite ( $file_out, fread( $file_in, 1024*512 ) );
                fclose( $file_in );
            } else $error = true;
            gzclose ( $file_out );
        } else $error = true;
        if ( $error ) return false;
        else return filesize($dest);
    }

}
?>
