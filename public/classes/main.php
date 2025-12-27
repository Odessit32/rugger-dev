<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

class main {
    private $hdl;

    public function __construct() {
        $this->hdl = database::getInstance();
    }

    public function getMainBanInfList($c_b_left = 0, $c_b_center = 0, $c_b_right = 0) {
        $c_b_left = intval($c_b_left) ?: 5;
        $c_b_center = intval($c_b_center) ?: 5;
        $c_b_right = intval($c_b_right) ?: 5;

        $classes = [];
        $list['center'] = $this->getMBIList($c_b_center, 'center');
        $list['left'] = $this->getMBIList($c_b_left, 'left');
        $list['right'] = $this->getMBIList($c_b_right, 'right');

        foreach (['center', 'left', 'right'] as $place) {
            if (is_array($list[$place]) && !empty($list[$place])) {
                foreach ($list[$place] as $item) {
                    if ($item['mbi_item_type'] == 'informer') {
                        $classes[] = $item['item']['i_class'];
                    }
                }
            }
        }
        $list['classes'] = $classes;
        return $list;
    }

    public function getMBIList($c_b, $place = 'center') {
        if (!in_array($place, ['center', 'left', 'right'])) {
            return false;
        }

        $temp = $this->hdl->selectElem(
            DB_T_PREFIX . "main_banner_informer",
            "*",
            "mbi_place = '$place' AND mbi_display_type = 'fixed' ORDER BY mbi_order DESC LIMIT 0, $c_b"
        );

        if (!$temp || count($temp) < $c_b) {
            $remaining = $c_b - ($temp ? count($temp) : 0);
            $t_c = $this->hdl->selectElem(
                DB_T_PREFIX . "main_banner_informer",
                "*",
                "mbi_place = '$place' AND mbi_display_type = 'shuffle' ORDER BY RAND() LIMIT 0, $remaining"
            );
            if ($t_c) {
                $temp = array_merge($temp ?: [], $t_c);
            }
        }

        if ($temp) {
            $q_banners = $q_informer = '';
            foreach ($temp as $item) {
                if ($item['mbi_item_type'] == 'banner') {
                    $q_banners .= " b_id = '" . $item['mbi_item_id'] . "' OR";
                } elseif ($item['mbi_item_type'] == 'informer') {
                    $q_informer .= " i_id = '" . $item['mbi_item_id'] . "' OR";
                }
            }

            $q_banners = $q_banners ? " AND (" . substr($q_banners, 0, -2) . ")" : '';
            $q_informer = $q_informer ? " AND (" . substr($q_informer, 0, -2) . ")" : '';

            $banners_id = [];
            $banners = $this->hdl->selectElem(
                DB_T_PREFIX . "banners",
                "b_id as id, b_is_active as is_active, b_is_datetime as is_datetime, b_datetime_from as datetime_from, b_datetime_to as datetime_to, b_url as url, b_path as path, b_target as target, b_noindex as noindex, b_title_" . S_LANG . " as title, b_type, b_code",
                "b_is_delete = 'no' AND (b_is_datetime = 'yes' OR (b_is_datetime = 'NO' AND b_datetime_from < NOW() AND b_datetime_to > NOW())) $q_banners ORDER BY b_id ASC"
            );
            if ($banners) {
                foreach ($banners as $item) {
                    $item['b_code'] = stripslashes($item['b_code']);
                    $banners_id[$item['id']] = $item;
                }
            }

            $informers_id = [];
            $informers = $this->hdl->selectElem(
                DB_T_PREFIX . "informers",
                "i_id as id, i_is_active as is_active, i_is_datetime as is_datetime, i_datetime_from as datetime_from, i_datetime_to as datetime_to, i_path, i_class, i_description_" . S_LANG . " as i_description, i_code, i_title_" . S_LANG . " as i_title",
                "i_is_delete = 'no' AND (i_is_datetime = 'yes' OR (i_is_datetime = 'NO' AND i_datetime_from < NOW() AND i_datetime_to > NOW())) $q_informer ORDER BY i_id ASC"
            );
            if ($informers) {
                foreach ($informers as &$item) {
                    $item['i_code'] = stripslashes($item['i_code']);
                    $informers_id[$item['id']] = $item;
                }
            }

            usort($temp, function($a, $b) {
                return $b['mbi_order'] <=> $a['mbi_order'];
            });

            for ($i = 0, $c_temp = count($temp); $i < $c_temp; $i++) {
                if (is_array($temp[$i])) {
                    if ($temp[$i]['mbi_item_type'] == 'banner' && isset($banners_id[$temp[$i]['mbi_item_id']])) {
                        $temp[$i]['item'] = $banners_id[$temp[$i]['mbi_item_id']];
                    } elseif ($temp[$i]['mbi_item_type'] == 'informer' && isset($informers_id[$temp[$i]['mbi_item_id']])) {
                        $temp[$i]['item'] = $informers_id[$temp[$i]['mbi_item_id']];
                    } else {
                        unset($temp[$i]);
                    }
                }
            }
        }
        return $temp;
    }
}
?>