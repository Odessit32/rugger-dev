<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class Tools
{
    private $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    public function prepareData($content_item = array(), $type='page') {
        if ($type == 'page') {
            include_once(__DIR__.'/page.php');
            $page = new page;
            $page_item = $page->getPageItem($content_item['p_id']);
            foreach ($page_item as $key=>$item) {
                if (empty($content_item[$key])) {
                    $content_item[$key] = $item;
                }
            }
        }
        if (!empty($content_item)) {
            $content_item['text'] = $this->doShortcodes($content_item['text']);
        }
        return $content_item;
    }

    private function doShortcodes($text = '') {
        if (!empty($text)) {
            // single photo shortcode
            preg_match_all("/\[photo\s+(\d*)\s*\]/i", $text, $photos_ids);
            if (!empty($photos_ids[1])) {
                $photos_html = array();
                foreach ($photos_ids[1] as $photo_item) {
                    $photos_html[] = $this->getHtmlShortcodePhoto($photo_item);
                }
                $text = str_replace($photos_ids[0], $photos_html, $text);
            }
            // ico image shortcode
            preg_match_all("/\[icon\s+(\S*)\s*(\S*)\s*\]/i", $text, $icon_ids);
            if (!empty($icon_ids[1])) {
                $icon_html = array();
                foreach ($icon_ids[1] as $icon_key => $icon_item) {
                    $icon_html[] = $this->getHtmlShortcodeIcon($icon_ids[1][$icon_key], $icon_ids[2][$icon_key]);
                }
                $text = str_replace($icon_ids[0], $icon_html, $text);
            }
        }
        return $text;
    }

    private function getHtmlShortcodeIcon($type = '', $name = '') {
        $html = '';
        if (!empty($type) && !empty($name)) {
            if ($type == 'material') {
                $html = '<div class="vc-icon-type-icon text-left mobile-center"><i class="material-icons ' . $name . '" style="font-size: 72px;"></i></div>';
            } elseif ($type == 'fa') {
                $html = '<div class="vc-icon-type-icon text-left mobile-center"><i class="fa fa-' . $name . '" style="font-size: 72px;"></i></div>';
            }
        }
        return $html;
    }

    private function getHtmlShortcodePhoto($id = 0) {
        global $imagepath;
        $html = '';
        $id = intval($id);
        if ($id > 0) {
            include_once(__DIR__ . '/photo.php');
            $photo = new photo;
            $photo_item = $photo->getPhotoItem($id);
            if (!empty($photo_item)) {
                $html = '<div class="featured-image padding-bottom-1x"><img src="' . $imagepath . 'upload/photos' . $photo_item['ph_big'] . '" alt="' . $photo_item['ph_title'] . '"></div>';
            }
        }
        return $html;
    }
}