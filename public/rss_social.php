<?php
	include_once('classes/config.php');
	include_once('classes/DB.php');

	$sitepath = SERVER.SITEPATH;
	$limit = 20; // количество последних новостей в ленте
	
	$db = new database;
	
	$mime_types = getMime();

    header('Content-type: application/rss+xml; charset=utf-8', true);
	
	// $get_id = $_GET['part'] - рубрика (Одесса, Украина, Мир...)
	if (isset($_GET['part'])) $get_id = intval($_GET['part']);
	if (!empty($get_id) && $get_id > 0) $q_export = " AND n_nc_id = '$get_id'";
	else $q_export = '';
	
	$news = $db->selectElem(DB_T_PREFIX."news",
        "   n_id as id,
            n_title_ru as title,
            n_nc_id,
            n_description_ru as description,
            n_text_ru as text,
            n_date_show",
        "   n_is_active = 'yes' AND
            n_date_show < NOW() AND
            n_is_export = 'yes' AND
            ( n_export_id = '' OR
            n_export_id = 0 )
            $q_export
        ORDER BY n_date_show DESC,
            n_id DESC
        LIMIT $limit");
	
	$rssContent = '<?xml version="1.0" encoding="utf-8"?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>Rugger.info</title>
		<link>http://'.$sitepath.'</link>
		<description>Всё регби на одном сайте. Самые свежие новости из мира овального мяча читайте на www.rugger.info</description>
		<image>
			<url>http://'.$sitepath.'logo.gif</url>
			<title>Rugger.info</title>
			<link>http://'.$sitepath.'</link>
		</image>
		<atom:link href="http://'.$sitepath.'rss_social.php" rel="self" type="application/rss+xml" />';
if ($news)
	foreach($news as $export){
		$rssContent.= "
		<item>
			<title><![CDATA[ ".htmlspecialchars_decode(strip_tags(stripslashes($export['title'])))." ]]></title>
			<link>http://".$sitepath."news/".$export['id']."</link>
			<guid>http://".$sitepath."news/".$export['id']."</guid>
			<description><![CDATA[ ".htmlspecialchars_decode(strip_tags(stripslashes($export['description'])))." ]]></description>\n";
		$rssContent .= getPhotoNews($export['id'], $db);
		$rssContent.= "			<pubDate>".date("r", strtotime($export['n_date_show']))."</pubDate>
		</item>";
	}
	$rssContent.="
	</channel>\n</rss>"; 
	echo $rssContent;
	
	
function getPhotoNews($id, &$db) {
	global $mime_types;
	global $sitepath;
	$echo_ = false;
	$id = intval($id);
	$photos = $db->selectElem(DB_T_PREFIX."photos",
		"	ph_id as id, 
			ph_path,
			ph_folder,
			ph_is_active,
			ph_is_informer,
			ph_type_main as is_main
			", "ph_type_id = '$id' AND ph_type = 'news' ORDER BY ph_type_main DESC, ph_id ASC LIMIT 1");

	if ($photos) {
		foreach ($photos as $item) {
			$file = "http://".$sitepath.'upload/photos'.$item['ph_folder'].$item['ph_path'];
			
			// ИСПРАВЛЕНИЕ: сначала explode, потом array_pop
			$parts = explode('.', $file);
			$ext = strtolower(array_pop($parts));

			$localPath = './upload/photos'.$item['ph_folder'].$item['ph_path'];
			$length = file_exists($localPath) ? filesize($localPath) : 0;

			$echo_ .= '			<enclosure url="'.$file.'" length="'.$length.'" type="'.$mime_types[$ext].'" />'."\n";
		}
	}
	return $echo_;
}
	
	function getMime(){
		$mime_types = array(

             'txt' => 'text/plain',
             'htm' => 'text/html',
             'html' => 'text/html',
             'php' => 'text/html',
             'css' => 'text/css',
             'js' => 'application/javascript',
             'json' => 'application/json',
             'xml' => 'application/xml',
             'swf' => 'application/x-shockwave-flash',
             'flv' => 'video/x-flv',

             // images
             'png' => 'image/png',
             'jpe' => 'image/jpeg',
             'jpeg' => 'image/jpeg',
             'jpg' => 'image/jpeg',
             'gif' => 'image/gif',
             'bmp' => 'image/bmp',
             'ico' => 'image/vnd.microsoft.icon',
             'tiff' => 'image/tiff',
             'tif' => 'image/tiff',
             'svg' => 'image/svg+xml',
             'svgz' => 'image/svg+xml'
		);
		return $mime_types;
	}
   
?>
