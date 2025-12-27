<?php
	include_once('classes/config.php');
	include_once('classes/DB.php');

	$sitepath = "http://".SERVER.SITEPATH;
	$limit = 20; // количество последних новостей в ленте
	
	$db = new database;

	header('Content-type: application/rss+xml; charset=utf-8', true);
	
	if (isset($_GET['id'])) $get_id = intval($_GET['id']);
	else $get_id = 0;
	if ($get_id == 2) $q_export = " AND n_is_export_drl = 'yes' AND ( n_export_id = '' OR n_export_id = 0 ) "; //DRLO
	elseif ($get_id == 3) $q_export = " AND n_is_export_fru = 'yes' AND ( n_export_id = '' OR n_export_id = 0 ) "; // FRU
	else $q_export = " AND n_is_export = 'yes' ";
	$news = $db->selectElem(DB_T_PREFIX."news",
        "   n_id as id,
            n_title_ru as title,
            n_nc_id,
            n_description_ru as description,
            n_text_ru as text,
            n_date_show",
        "   n_is_active = 'yes' AND
            n_date_show < NOW()
            $q_export
            ORDER BY n_date_show DESC,
            n_id DESC
            limit $limit");
	
	$news_categories = $db->selectElem(DB_T_PREFIX."news_categories","nc_id as id, nc_title_ru as title","");
	if ($news_categories) foreach($news_categories as $item) $news_categories_id[$item['id']] = trim($item['title']);
	

	$rssContent = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
                        <channel>
                            <title>Rugger.info</title>
                            <link>".$sitepath."</link>
                            <description></description>
                            <image>
                                <url>".$sitepath."/images/logo.png</url>
                                <title>Rugger.info</title>
                                <link>".$sitepath."</link>
                            </image>
                            <atom:link href=\"".$sitepath."rss.php\" rel=\"self\" type=\"application/rss+xml\" />";
	if ($news)
	foreach($news as $export){
           $rssContent.= "
		<item>
			<title><![CDATA[".htmlspecialchars_decode(trim(stripslashes($export['title'])))."]]></title>
			<link>".$sitepath."news/".$export['id']."</link>
			<guid>".$sitepath."news/".$export['id']."</guid>
			".((!empty($news_categories_id[$export['n_nc_id']]))?"<category>".$news_categories_id[$export['n_nc_id']]."</category>":'')."
			<description><![CDATA[".htmlspecialchars_decode(trim(stripslashes(strip_tags($export['description'], "<p>, <b>, <a>"))))."]]></description>\n";
		if ($get_id == 2 OR $get_id == 3) {
			$rssContent.= "			<fulltext><![CDATA[".htmlspecialchars_decode(trim(stripslashes(strip_tags($export['text'], "<p>, <b>, <a>"))))."]]></fulltext>";
			$rssContent .= "			".getPhotoNews($export['id'], $db);
			$rssContent .= "			".getVideoNews($export['id'], $db);
		}
		$rssContent.= "			<pubDate>".date("r", strtotime($export['n_date_show']))."</pubDate>
		</item>";
	}
	$rssContent.="
	</channel>\n</rss>"; 
	echo $rssContent;
   
	function getVideoNews($id, &$db) {
		$echo_ = false;
		$id = intval($id);
		$videos = $db->selectElem(DB_T_PREFIX."videos",
						"	v_id as id, 
							v_code,
							v_title_".D_S_LANG." as v_title,
							v_about_".D_S_LANG." as v_about,
							v_folder,
							v_is_active
							","v_type_id = '$id' AND v_type = 'news' ORDER BY v_id ASC");
		if ($videos){
			foreach ($videos as $item){
				$echo_ .= "\n".'			<video code="'.$item['v_code'].'" title="'.strip_tags($item['v_title']).'" about="'.strip_tags($item['v_about']).'" active="'.$item['v_is_active'].'" />';
			}
		}
		return $echo_."\n";
	}
	
	function getPhotoNews($id, &$db) {
		global $sitepath;
		$echo_ = false;
		$id = intval($id);
		$photos = $db->selectElem(DB_T_PREFIX."photos",
						"	ph_id as id, 
							ph_path,
							ph_folder,
							ph_about_".D_S_LANG." as ph_about,
							ph_is_active,
							ph_is_informer,
							ph_type_main as is_main
							","ph_type_id = '$id' AND ph_type = 'news' ORDER BY ph_id ASC");
		if ($photos){
			foreach ($photos as $item){
				$file = $sitepath.'upload/photos'.$item['ph_folder'].$item['ph_path'];
				$type = strtolower(substr(strrchr($item['ph_path'], "."), 1));
				$echo_ .=  "\n".'			<photo url="'.$file.'" about="'.strip_tags($item['ph_about']).'" main="'.$item['is_main'].'" informer="'.$item['ph_is_informer'].'" active="'.$item['ph_is_active'].'" />';
			}
		}
		return $echo_;
	}
   
?>