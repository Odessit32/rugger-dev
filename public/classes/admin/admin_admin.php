<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class admin{
    private $hdl;
    public function __construct(){
        $this->hdl = database::getInstance();
    }

    public function getAdminsList(){
        $temp['admin'] = $this->hdl->selectElem(DB_T_PREFIX."admins","*","a_admin_status = 'yes'");
        $temp['editor'] = $this->hdl->selectElem(DB_T_PREFIX."admins","*","a_admin_status != 'yes' AND a_publisher_status = 'yes'");
        $temp['other'] = $this->hdl->selectElem(DB_T_PREFIX."admins","*","a_admin_status != 'yes' AND a_publisher_status != 'yes'");
        return $temp;
    }

    public function getAdminItem($item = 0){
        $item = intval($item);
        if ($item >0) {
            $temp = $this->hdl->selectElem(DB_T_PREFIX."admins","*","a_id=$item");
            return $temp[0];
        } else return false;
    }

    public function updatePassAdmin(){
        $_POST['a_id'] = intval($_POST['a_id']);
        if ($_POST['a_id']<1) return false;
        $temp = $this->hdl->selectElem(DB_T_PREFIX."admins","a_passwd","a_id='".$_POST['a_id']."' LIMIT 1");
        if ($temp) {
            $temp = $temp[0];
            if ($temp['a_passwd'] == md5($_POST['old_passwd'])){
                $elems = array(
                    "a_passwd"=>md5($_POST['new_passwd'])
                );
                $condition = array(
                    "a_id"=>$_POST['a_id']
                );
                if ($this->hdl->updateElem(DB_T_PREFIX."admins",$elems, $condition)) return true;
            }
        }
        return false;
    }

    public function updateAdmin(){
        $_POST['a_id'] = intval($_POST['a_id']);
        if ($_POST['a_id']<1) return false;
        if ($_POST['a_is_active']) $is_active = 'yes';
        else $is_active = 'no';
        $elems = array(
            "a_is_active"=>$is_active,
            "a_name"=>$_POST['a_name'],
            "a_f_name"=>$_POST['a_f_name'],
            "a_o_name"=>$_POST['a_o_name'],
            "a_about"=>$_POST['a_about'],
            "a_email"=>$_POST['a_email']
        );
        $condition = array(
            "a_id"=>$_POST['a_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."admins",$elems, $condition)) return true;
        else return false;
    }

    public function updateRightsAdmin(){
        $_POST['a_id'] = intval($_POST['a_id']);
        if ($_POST['a_id']<1) return false;
        if ($_POST['is_admin'] and USER_IS_ADMIN == 'yes') $is_admin = 'yes';
        else $is_admin = 'no';
        if ($_POST['is_publisher']) $is_publisher = 'yes';
        else $is_publisher = 'no';
        $elems = array(
            "a_admin_status"=>$is_admin,
            "a_publisher_status"=>$is_publisher
        );
        $condition = array(
            "a_id"=>$_POST['a_id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."admins",$elems, $condition)) return true;
        else return false;
    }

    public function createAdmin(){
        if ($_POST['is_admin'] and USER_IS_ADMIN == 'yes') $is_admin = 'yes';
        else $is_admin = 'no';
        if ($_POST['is_publisher']) $is_publisher = 'yes';
        else $is_publisher = 'no';
        if ($_POST['a_is_active']) $is_active = 'yes';
        else $is_active = 'no';
        $elem = array(
            $_POST['user_login'],
            md5($_POST['passwd']),
            addslashes($_POST['a_name']),
            addslashes($_POST['a_f_name']),
            addslashes($_POST['a_o_name']),
            addslashes($_POST['a_email']),
            addslashes($_POST['a_about']),
            $is_active,
            'NOW()',
            '',
            $is_admin,
            $is_publisher,
            USER_ID,
            'no'
        );

        if ($this->hdl->addElem(DB_T_PREFIX."admins", $elem)) return true;
        else return false;
    }

    public function deleteAdmin($id){
        $elems = array(
            "a_is_delete"=>'yes'
        );
        $condition = array(
            "a_id"=>$_POST['id']
        );
        if ($this->hdl->updateElem(DB_T_PREFIX."admins",$elems, $condition)) return true;
    }
}
?>
