<?php
	// КРЕДО 63
	// переменные и БД
	
	include_once('classes/config.php');
	include_once('classes/DB.php');
	
	$db = new database;
	
	if (isset($_GET['id'])) $get_id = intval($_GET['id']);
	else $get_id = 0;
	
	// вызов функций ///////////////////////////////////////////////////////////////
	
	$all_rss = getRSSArray(); // массив источников RSS
	$categories = getCategory();
	
	if ($get_id>0){
		ReadRSS($all_rss[$get_id]); // читаем только один источник
	} else {
		for ($i=1; $i<=count($all_rss); $i++) ReadRSS($all_rss[$i]); // читаем все источники по порядку
	}
	
	//чтение источника /////////////////////////////////////////////////////////////
	
	function ReadRSS($next_rss){ // разбор файла RSS
		//$next_rss
		//'source' => 'http://liga.rugbyodessa.com/rss.php?id=2',
		//'title' => 'Детская регбийная лига Одессы',
		//'short' => 'drlo'
		global $db;
		global $categories;
		
		$file = GetRssFile($next_rss['source']);
		if ($file){
			$count_news = 0;
			$rss_news_text = '';
			$errors = '';
			$xml = simplexml_load_file($file);
			//var_dump($xml);
			if ($xml->channel->item)
			foreach ($xml->channel->item as $item) {
				$n_id = $next_rss['short']."_".intval($item->guid);
				if(!checkNewsExist($n_id)){ 
					echo $n_id."<br>\n";
					
					$item->category = iconv("UTF-8", "cp1251", $item->category);
					$item->category_id = 0;
					foreach ($categories as $key_c=>$item_c) if ($item_c == $item->category) $item->category_id = $key_c;
					
					$item->title = addslashes(iconv("UTF-8", "cp1251", $item->title)); 
					$item->link = iconv("UTF-8", "cp1251", $item->link); 
					$item->description = addslashes(iconv("UTF-8", "cp1251", $item->description));
					$item->fulltext = addslashes(iconv("UTF-8", "cp1251", $item->fulltext));
					$date = date("Y-m-d", strtotime($item->pubDate));
					$item->tags = addslashes(iconv("UTF-8", "cp1251", $item->tags));
					$elem = array(
						$item->title, 
						'', 
						'', 
						$item->description, 
						'', 
						'', 
						$item->fulltext, 
						'', 
						'', 
						'yes',
						'NOW()',
						'NOW()',
						0,
						$date,
						$item->category_id,
						$item->tags, 
						'no',
						'no',
						'no',
						$n_id,
						$next_rss['title'],
                        0,
                        'yes'
					); 
					$news_id = $db->addElem(DB_T_PREFIX."news", $elem);
					if ($item->photo){
						$phg_id = 0;
						//////////////////////////////////////////////////////////////////
						if (count($item->photo)>1){ // сделать фото галерею
							$elem = array(
								$item->title,
                                '',
                                '',
                                "<p>Фото галерея к новости &laquo;".$item->title."&raquo;.</p>",
                                '',
                                '',
                                'NOW()',
                                'NOW()',
                                0,
                                'yes',
                                'news',
                                $news_id,
                                0,
                                $date,
                                'no',
                                count($item->photo)
							); 
							$phg_id = $db->addElem(DB_T_PREFIX."photo_gallery", $elem);
						}
						///////////////////////////////////////////////////////////////////
						$i = 1;
						foreach ($item->photo as $photo) {
							$file_name = "news_".$news_id."_".$i.strrchr($photo['url'], ".");
							//var_dump($photo);
							if (copy($photo['url'], "./upload/photos/news/".$file_name)){
								$photo['main'] = ($photo['main'] == 'yes') ? 'yes' : 'no';
								$photo['active'] = ($photo['active'] == 'yes') ? 'yes' : 'no';
								$photo['informer'] = ($photo['informer'] == 'yes') ? 'yes' : 'no';
								resizePhoto("./upload/photos/news/".$file_name, $photo['main']);
								$elem = array(
									$file_name,
									addslashes(iconv("UTF-8", "cp1251", $photo['about'])),
                                    '',
                                    '',
									'NOW()', 
									'NOW()', 
									0, 
									'/news/', 
									$photo['active'], 
									$phg_id, 
									$photo['informer'], 
									'news', 
									$news_id,
									$photo['main'],
                                    '0'
								); 
								//var_dump($elem);
								$db->addElem(DB_T_PREFIX."photos", $elem);
							}
							$i++;
						}
					}
					if ($item->video){
						$vg_id = 0;
						//////////////////////////////////////////////////////////////////
						if (count($item->video)>0){ // сделать видео галерею
							$elem = array(
								$item->title,
                                '',
                                '',
								"<p>Видео галерея к новости &laquo;".$item->title."&raquo;.</p>",
                                '',
                                '',
								'NOW()', 
								'NOW()', 
								0, 
								'yes', 
								'news', 
								$news_id,
								0,
								$date,
                                'no',
                                count($item->video)
							); 
							$vg_id = $db->addElem(DB_T_PREFIX."video_gallery", $elem);
						}
						///////////////////////////////////////////////////////////////////
						$i = 1;
						foreach ($item->video as $video) {
							$file_name = "news_".$news_id."_".$i.".jpg";
							if (copy("http://i1.ytimg.com/vi/".$video['code']."/hqdefault.jpg", "./upload/video_thumbs/news/".$file_name)){
								$elem = array(
									$video['code'],
									addslashes(iconv("UTF-8", "cp1251", $video['title'])),
                                    '',
                                    '',
									addslashes(iconv("UTF-8", "cp1251", $video['about'])),
                                    '',
                                    '',
									'NOW()', 
									'NOW()', 
									0, 
									'/news/', 
									$video['active'], 
									$vg_id, 
									'news', 
									$news_id
								); 
								//var_dump($elem);
								$v_id = $db->addElem(DB_T_PREFIX."videos", $elem);
								rename("./upload/video_thumbs/news/".$file_name, "./upload/video_thumbs/news/".$v_id.".jpg");
								resizeVideoThumb("./upload/video_thumbs/news/".$v_id.".jpg");
							}
							$i++;
						}
					}
				}
			}
		}
		DeleteFileRSS($file);
	}
	
	function DeleteFileRSS($file){ // удаление файла
		unlink ( $file );
	}
	
	function GetRssFile($file = ''){ // запись файла из источника
		if ($file != ''){
			$newFileName = './temp_rss/'.time().'.rss';
			if (copy($file, $newFileName)) {
				return $newFileName;
			} else {
				return false;
			}
		}
	}
	
	function checkNewsExist($linkId){
		global $db;
		$news = $db->selectElem(DB_T_PREFIX."news","n_id as id","n_export_id ='$linkId' limit 1");
		if ($news) return $news[0]['id'];
		return false;
	}
	
	function resizePhoto($file = false, $main = 'no'){
		$is_informer = true ; // вкл./откл. делать информер
		$is_medium = true ; // вкл./откл. делать средний размер
		if (!$file) return false;
		if ($main == 'yes') $main = 'yes';
		else $main = 'no';
		// максимальный размер большой картинки
		$max = 600;
		// максимальный размер средней картинки
		$max_m = 220;
		// максимальная ширина главной картинки
		$main_x = 480;
		// размеры превьюхи
		$p_x = 150;
		$p_y = 100;
		// размеры информера
		$i_x = 50;
		$i_y = 50;
		$image_size = getimagesize($file);
		
		$type = "image/".strtolower(substr(strrchr($file, "."), 1));
		
		// JPG /////////////////////////////////////////////////////////////////////////////////////////////////
		if ($type == 'image/jpeg' or $type == 'image/jpg') $im = imagecreatefromjpeg($file);
		// GIF /////////////////////////////////////////////////////////////////////////////////////////////////
		if ($type == 'image/gif') $im = ImageCreateFromGif($file);
		// PNG /////////////////////////////////////////////////////////////////////////////////////////////////
		if ($type == 'image/png') $im = imagecreatefrompng($file);
		if ($im){
			// пережимаем превью 
			$p_aspect = $p_x / $p_y;
			$aspect = $image_size[0] / $image_size[1];
			if ($aspect >= $p_aspect){
				$s_y = $image_size[1];
				$s_x = round($s_y*$p_aspect);
				$src_y = 0;
				$src_x = round(($image_size[0]-$s_x)/2);
			} else {
				$s_x = $image_size[0];
				$s_y = round($s_x/$p_aspect);
				$src_x = 0;
				$src_y = round(($image_size[1]-$s_y)/2);
			}
			if ($image_size[0]>=$p_x) $dest_x = 0;
			else $dest_x = round(($p_x - $image_size[0]) / 2);
			if ($image_size[1]>=$p_y) $dest_y = 0;
			else $dest_y = round(($p_y - $image_size[1]) / 2);
			$nim = imagecreatetruecolor($p_x,$p_y);
			$white = imagecolorallocate($nim, 255, 255, 255);
			imagefilledrectangle($nim, 0, 0, $p_x, $p_y, $white);
			if ($image_size[0]<$p_x) $p_x = $image_size[0];
			if ($image_size[1]<$p_y) $p_y = $image_size[1];
			imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $p_x, $p_y, $s_x, $s_y);
			$n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-small".strrchr($file, ".");
			if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
			if ($type == 'image/gif') imagegif($nim, $n_file);
			if ($type == 'image/png') imagepng($nim, $n_file);
			imagedestroy($nim);
			
			// пережимаем информер 
			if ($is_informer) {
				$i_aspect = $i_x / $i_y;
				$aspect = $image_size[0] / $image_size[1];
				if ($aspect >= $i_aspect){
					$s_y = $image_size[1];
					$s_x = round($s_y*$i_aspect);
					$src_y = 0;
					$src_x = round(($image_size[0]-$s_x)/2);
				} else {
					$s_x = $image_size[0];
					$s_y = round($s_x/$i_aspect);
					$src_x = 0;
					$src_y = round(($image_size[1]-$s_y)/2);
				}
				if ($image_size[0]>=$i_x) $dest_x = 0;
				else $dest_x = round(($i_x - $image_size[0]) / 2);
				if ($image_size[1]>=$i_y) $dest_y = 0;
				else $dest_y = round(($i_y - $image_size[1]) / 2);
				$nim = imagecreatetruecolor($i_x,$i_y);
				$white = imagecolorallocate($nim, 255, 255, 255);
				imagefilledrectangle($nim, 0, 0, $i_x, $i_y, $white);
				if ($image_size[0]<$i_x) $i_x = $image_size[0];
				if ($image_size[1]<$i_y) $i_y = $image_size[1];
				imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $i_x, $i_y, $s_x, $s_y);
				$n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-informer".strrchr($file, ".");
				if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
				if ($type == 'image/gif') imagegif($nim, $n_file);
				if ($type == 'image/png') imagepng($nim, $n_file);
				imagedestroy($nim);
			}
			
			// пережимаем главную картинку 
			if ($main == 'yes') {
				if ($max < $image_size[0] or $max < $image_size[1]){
					$aspect = $image_size[0] / $image_size[1];
					$main_y = $main_x/$aspect;
				} else {
					$main_x = $image_size[0];
					$main_y = $image_size[1];
				}
				$nim = imagecreatetruecolor($main_x,$main_y);
				imagecopyresampled($nim, $im, 0, 0, 0, 0, $main_x, $main_y, $image_size[0], $image_size[1]);
				$n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-s_main".strrchr($file, ".");
				if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
				if ($type == 'image/gif') imagegif($nim, $n_file);
				if ($type == 'image/png') imagepng($nim, $n_file);
				imagedestroy($nim);
			}
			
			// пережимаем среднюю картинку 
			if ($is_medium == 'yes') {
				$aspect = $image_size[0] / $image_size[1];
				$max_m_y = $max_m/$aspect;
				$nim = imagecreatetruecolor($max_m,$max_m_y);
				imagecopyresampled($nim, $im, 0, 0, 0, 0, $max_m, $max_m_y, $image_size[0], $image_size[1]);
				$n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-med".strrchr($file, ".");
				if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $n_file);
				if ($type == 'image/gif') imagegif($nim, $n_file);
				if ($type == 'image/png') imagepng($nim, $n_file);
				imagedestroy($nim);
			}
			
			// пережимаем большую копию
			if ($image_size[0]>$max or $image_size[1]>$max) {
				if ($image_size[0]>=$image_size[1]) {
					$aspect = $image_size[1] / $image_size[0];
					$nw = $max;
					$nh = round($nw * $aspect);
				} else {
					$aspect = $image_size[0] / $image_size[1];
					$nh = $max;
					$nw = round($nh * $aspect);
				}
				$nim = imagecreatetruecolor($nw,$nh);
				imagecopyresampled($nim, $im, 0,0,0,0,$nw,$nh,$image_size[0],$image_size[1]);
				if ($type == 'image/jpeg' or $type == 'image/jpg') imagejpeg($nim, $file);
				if ($type == 'image/gif') imagegif($nim, $file);
				if ($type == 'image/png') imagepng($nim, $file);
				imagedestroy($nim);
			}
		}
	}
	
	function resizeVideoThumb($file = false){
		if (!$file) return false;
		$type = strtolower(substr(strrchr($file, "."), 1));
		if ($type == '') return false;
		// максимальный размер большой картинки
		$max = 500;
		// размеры превьюхи
		$p_x = 220;
		$p_y = 150;
		
		$image_size = getimagesize($file);
		
		// JPG /////////////////////////////////////////////////////////////////////////////////////////////////
		if ($type == 'jpeg' or $type == 'jpg'){
			$im = imagecreatefromjpeg($file);
			// пережимаем превью 
			$p_aspect = $p_x / $p_y;
			$aspect = $image_size[0] / $image_size[1];
			if ($aspect >= $p_aspect){
				$s_y = $image_size[1];
				$s_x = round($s_y*$p_aspect);
				$src_y = 0;
				$src_x = round(($image_size[0]-$s_x)/2);
			} else {
				$s_x = $image_size[0];
				$s_y = round($s_x/$p_aspect);
				$src_x = 0;
				$src_y = round(($image_size[1]-$s_y)/2);
			}
			if ($image_size[0]>=$p_x) $dest_x = 0;
			else $dest_x = round(($p_x - $image_size[0]) / 2);
			if ($image_size[1]>=$p_y) $dest_y = 0;
			else $dest_y = round(($p_y - $image_size[1]) / 2);
			$nim = imagecreatetruecolor($p_x,$p_y);
			$white = imagecolorallocate($nim, 255, 255, 255);
			imagefilledrectangle($nim, 0, 0, $p_x, $p_y, $white);
			if ($image_size[0]<$p_x) $p_x = $image_size[0];
			if ($image_size[1]<$p_y) $p_y = $image_size[1];
			imagecopyresampled($nim, $im, $dest_x, $dest_y, $src_x, $src_y, $p_x, $p_y, $s_x, $s_y);
			$n_file = substr($file, 0, strlen(strrchr($file, "."))*(-1))."-small".strrchr($file, ".");
			imagejpeg($nim, $n_file);
			imagedestroy($nim);
			
			// пережимаем большую копию
			if ($image_size[0]>$max or $image_size[1]>$max) {
				if ($image_size[0]>=$image_size[1]) {
					$aspect = $image_size[1] / $image_size[0];
					$nw = $max;
					$nh = round($nw * $aspect);
				} else {
					$aspect = $image_size[0] / $image_size[1];
					$nh = $max;
					$nw = round($nh * $aspect);
				}
				$nim = imagecreatetruecolor($nw,$nh);
				imagecopyresampled($nim, $im, 0,0,0,0,$nw,$nh,$image_size[0],$image_size[1]);
				imagejpeg($nim, $file);
				imagedestroy($nim);
			}
		}
	}
	
	function getRSSArray(){
		$all_rss = array(
			'1' => array(	// для КРЕДО из ДРЛО
				'source' => 'http://liga.rugbyodessa.com/rss.php?id=2',
				'title' => 'Детская регбийная лига Одессы',
				'short' => 'drlo'
				), 
			'2' => array(	// для КРЕДО из FRU
				'source' => 'http://rugby.org.ua/rss.php?id=3',
				'title' => 'Федерація регбі України',
				'short' => 'fru'
				)
		);
		return $all_rss;
	}
	
	function getCategory(){
		global $db;
		$res = false;
		$news = $db->selectElem(DB_T_PREFIX."news_categories","nc_id as id, nc_title_ru as title","1 OREDER BY nc_id ASC");
		if ($news) foreach ($news as $item) $res[$item['id']] = $item['title'];
		return $res;
	}
?>