<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
class login{
	protected $hdl;
	private $login;
	private $login_lifespan=7200;
	private $loginRights;
	function __construct(){
		$this->hdl = database::getInstance();
		if(isset($_SESSION['login_name'])) {
			$search = array(" ", "'", '"', 'UNION', ';');
			$replace = array("_", '', '', '', '');
			$ses_login_name = str_replace($search, $replace, $_SESSION['login_name']);
			$id = $this->hdl->selectElem(DB_T_PREFIX."sessions", "s_u_id, s_id", "s_hash='$ses_login_name' AND s_u_type = 'admin' AND s_datetime_end > NOW()");
			if (!empty($id) && isset($id[0]['s_u_id']) && $id[0]['s_u_id'] > 0) {
				$u_id = $id[0]['s_u_id'];
				$login_temp = $this->hdl->selectElem(DB_T_PREFIX."admins ", "*", "a_id='$u_id' AND a_is_active = 'yes'");
				if (!empty($login_temp) && isset($login_temp[0]['a_id']) && $login_temp[0]['a_id'] > 0) {
					$this->login = $login_temp[0];
					$s_datetime_end = time()+$this->login_lifespan;
					$s_datetime_end = date("Y-m-d G:i:s", $s_datetime_end);
					$elems = array('s_datetime_start' => 'NOW()', 's_datetime_end' => $s_datetime_end);
					$condition = array('s_id' => $id[0]['s_id']);
					$this->hdl->updateElem(DB_T_PREFIX."sessions", $elems, $condition);
				} else {
					session_destroy();
					header('Location: '.$_SERVER['REQUEST_URI']);
				}
			} else {
				session_destroy();
				header('Location: '.$_SERVER['REQUEST_URI']);
			}
		}
	}

	function login($login, $psw){
		$search = array(" ", "'", '"', 'UNION', ';');
		$replace = array("_", '', '', '', '');
		$login = str_replace($search, $replace, $login);
		//$psw = str_replace($search, $replace, $psw);
		$md5psw = md5($psw);
		$login_temp = $this->hdl->selectElem(DB_T_PREFIX."admins", "*", "`a_login` = '$login' AND `a_passwd` = '$md5psw' AND `a_is_active` = 'yes'");
		if($login_temp){ 
			$login_temp = $login_temp[0];
			if ($login_temp['a_id'] < 1) return false;
			$s_hash = time().$login;
			$s_hash = md5($s_hash);
			$this->setSessions($login_temp['a_id'], $s_hash);
			$_SESSION['login_name'] = $s_hash;
			
			$rights = array('a_date_time_last_login' => 'NOW()');
			$condition = array('a_id' => $login_temp['a_id']);
			$this->hdl->updateElem(DB_T_PREFIX."admins", $rights, $condition);
			return true;
		} else return false;
	}

	function setSessions($a_id, $hash){
		$s_datetime_end = time()+$this->login_lifespan;
		$s_datetime_end = date("Y-m-d G:i:s", $s_datetime_end);
		$temp = $this->hdl->selectElem(DB_T_PREFIX."sessions", "s_id", "`s_u_id` = '$a_id'");
		if ($temp[0]['s_id'] > 0) {
			$rights = array('s_datetime_start' => 'NOW()', 's_datetime_end' => $s_datetime_end, 's_hash' => $hash);
			$condition = array('s_id' => $temp[0]['s_id']);
			$this->hdl->updateElem(DB_T_PREFIX."sessions", $rights, $condition);
		} else {
			$fields = array($hash, $a_id, 'admin', 'NOW()', $s_datetime_end);
			$this->hdl->addElem(DB_T_PREFIX."sessions", $fields);
		}
	}

	function getLoginSession(){
		$id = $this->hdl->selectElem(DB_T_PREFIX."sessions", "s_u_id, s_id", "s_hash='".$_SESSION['login_name']."' AND s_u_type = 'admin' AND s_datetime_end > NOW()", false, false);
		if (!empty($id) && isset($id[0]['s_u_id']) && $id[0]['s_u_id'] > 0) {
			$u_id = $id[0]['s_u_id'];
			$login_temp = $this->hdl->selectElem(DB_T_PREFIX."admins ", "*", "a_id='$u_id' AND a_is_active = 'yes'", false, false);
			if (!empty($login_temp) && isset($login_temp[0]['a_id']) && $login_temp[0]['a_id'] > 0) {
				$this->login = $login_temp[0];
				define('USER_ID', $this->login['a_id']);
				define('USER_LOGIN', $this->login['a_login']);
				define('USER_NAME', $this->login['a_name']);
				define('USER_IS_ADMIN', $this->login['a_admin_status']);
				define('USER_IS_PUBLISHER', $this->login['a_publisher_status']);
				$s_datetime_end = time()+$this->login_lifespan;
				$s_datetime_end = date("Y-m-d G:i:s", $s_datetime_end);
				$rights = array('s_datetime_start' => 'NOW()', 's_datetime_end' => $s_datetime_end);
				$condition = array('s_id' => $id[0]['s_id']);
				$this->hdl->updateElem(DB_T_PREFIX."sessions", $rights, $condition);
				return $this->login;
			} else {
				session_destroy();
				header('Location: '.$_SERVER['REQUEST_URI']);
			}
		}
		return false;
	}

	function getDelSession(){
		$this->hdl->delElem(DB_T_PREFIX."sessions", "s_hash='$_SESSION[login_name]'");
	}

	function checkAdminRights(){
		$temp = $this->hdl->selectElem(DB_T_PREFIX."permissions", "*", "pr_u_id = '".USER_ID."'");
		for ($i=0; $i<count($temp); $i++){
			$temp_sec = $this->hdl->selectElem(DB_T_PREFIX."section", "*", "sec_id = '".$temp[$i]['pr_sec_id']."' LIMIT 1");
			$temp[$i]['section'][$temp[$i]['pr_sec_id']] = $temp_sec[0];
			$f = 0;
			$temp_vars = $this->hdl->selectElem(DB_T_PREFIX."section_vars", "*", "secv_sec_id = '".$temp[$i]['pr_sec_id']."'");
			if ($temp_vars)
				foreach($temp_vars as $item) {
					//$temp[$i]['vars'][$temp[$i]['pr_sec_id']][$item['secv_name']] = $item['secv_value'];
					if (isset($_GET[$item['secv_name']])) 
						if ($_GET[$item['secv_name']] == $item['secv_value']) 
							$f++;
				}
			if ($f == count($temp_vars))
				if ($temp[$i]['section'][$temp[$i]['pr_sec_id']]['sec_is_other_vars'] == 'yes') return true;
				else if ($f == count($_GET)) return true;
		}
		return false;
	}

	function checkAdminMenuItemRights($url = ''){
		
		if (USER_IS_ADMIN == 'yes') return true;
		if ($url == '') return false;
		
		// ������ ������ URL
		if (substr($url, 0, 1) == '?') $url = substr($url, 1);
		parse_str($url, $url_vars);
		// ������ ���� ������������ � ��������
		$temp = $this->hdl->selectElem(DB_T_PREFIX."permissions", "*", "pr_u_id = '".USER_ID."'");
		for ($i=0; $i<count($temp); $i++){
			$temp_sec = $this->hdl->selectElem(DB_T_PREFIX."section", "*", "sec_id = '".$temp[$i]['pr_sec_id']."' LIMIT 1");
			$temp[$i]['section'][$temp[$i]['pr_sec_id']] = $temp_sec[0];
			$f = 0;
			$temp_vars = $this->hdl->selectElem(DB_T_PREFIX."section_vars", "*", "secv_sec_id = '".$temp[$i]['pr_sec_id']."'");
			if ($temp_vars)
				foreach($temp_vars as $item) {
					//$temp[$i]['vars'][$temp[$i]['pr_sec_id']][$item['secv_name']] = $item['secv_value'];
					if (isset($url_vars[$item['secv_name']])) 
						if ($url_vars[$item['secv_name']] == $item['secv_value']) 
							$f++;
				}
			if ($f == count($temp_vars))
				if ($temp[$i]['section'][$temp[$i]['pr_sec_id']]['sec_is_other_vars'] == 'yes') return true;
				else if ($f == count($url_vars)) return true;
		}
		return false;
	}

	function checkActivity($id){
		return $this->hdl->selectElem(DB_T_PREFIX."admins", "is_active","a_id=$id");
	}

	function changeActivity($id, $active){
		$elems = array(
			"a_is_active"=>$active
		);
		$condition = array(
			"a_id" => $id
		);
		$this->hdl->updateElem(DB_T_PREFIX."admins",$elems, $condition);
	}

	function getLoginInfo(){
		return $this->login;
	}

	function setLoginInfo($info){  
		if(is_array($info) and sizeof($info)>0){  
			$condition = array(
				"a_id"=>$this->login[0]['id']
			);
			$this->hdl->updateElem(DB_T_PREFIX."admins", $info, $condition);
		}
	}

	function __destruct(){  
		//$this->hdl->disconnect();
	} 

	function logout(){  
		$this->session->logout();   
	}
}
  
  
?>
