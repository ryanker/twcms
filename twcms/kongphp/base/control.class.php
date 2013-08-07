<?php
// +------------------------------------------------------------------------------
// | Copyright (C) 2013 wuzhaohuan <kongphp@gmail.com> All rights reserved.
// +------------------------------------------------------------------------------

class control{
	public function __get($var) {
		if($var == 'view') {
			return $this->view = new view();
		}elseif($var == 'db') {
			$db = 'db_'.$_SERVER['_config']['db']['type'];
			return $this->db = new $db($_SERVER['_config']['db']);	// 给开发者调试时使用，不建议在控制器中操作 DB
		}else{
			return $this->$var = M($var);
		}
	}

	public function assign($k, &$v) {
		$this->view->assign($k, $v);
	}

	public function assign_value($k, $v) {
		$this->view->assign_value($k, $v);
	}

	public function display($filename = null) {
		$this->view->display($filename);
	}

	public function message($status, $message, $jumpurl = '', $delay = 2) {
		if(R('ajax')) {
			echo json_encode(array('kong_status'=>$status, 'message'=>$message, 'jumpurl'=>$jumpurl, 'delay'=>$delay));
		}else{
			if(empty($jumpurl)) {
				$jumpurl = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
			}
			include KONG_PATH.'tpl/sys_message.php';
		}
		exit;
	}

	public function __call($method, $args) {
		$controlname = get_class($this);
		if(DEBUG) {
			throw new Exception('控制器没有找到：'.$controlname.'->'.$method.'('.(empty($args) ? '' : var_export($args, 1)).')');
		}else{
			core::error404($controlname);
		}
	}
}
