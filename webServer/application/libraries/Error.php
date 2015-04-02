<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error {
	private $CI;
	private $_error_code = null;
	private $_error_msg  = null;
	
	function __construct($config = array()) {
		require_once 'Tuxedo/inc/error.inc.php';
		$this->CI =& get_instance();
	}
	
	//返回调用成功返回的错误码
	function get_success() {
		return '0000';
	}
	
	//返回错误码
	function get_error() {
		return $this->_error_code;
	}
	
	//设置错误码
	function set_error($error_code) {
		$argv = func_get_args();
		$this->_error_code = $error_code;
		$this->_error_msg  = call_user_func_array(array($this, 'error_msg'), $argv);
	}
	
	//php统一登录错误码和原错误码定义重复，需要转义
	function set_login_error($error_code) {
		$this->set_error(Err::lgnerr_conv($error_code));
	}
	
	//返回上次TUX调用是否失败
	function error() {
		if($this->_error_code != '0000') {
			return true;
		}
		return false;
	}
	
	//展现成功提示页
	function show_success($error_msg = null) {
		if($error_msg == null) {
			$error_msg = $this->error_msg();
		}
		ob_start();
		$this->CI->template->set('main_page', 'hidden');
		$buffer = $this->CI->template->load('default', 'error/success', array('error_msg' => $error_msg), true);
		ob_end_clean();
		echo $buffer;
		exit;
// 		echo '<h1>'.$error_msg.'</h1>';
// 		exit;
	}	
	
	//展现错误页
	function show_error($error_msg = null) {
		if($error_msg == null) {
			$error_msg = $this->error_msg();
		}
		
		ob_start();
		$this->CI->template->set('main_page', 'hidden');
		$buffer = $this->CI->template->load('default', 'error/error', array('error_msg' => $error_msg), true);
		ob_end_clean();
		echo $buffer;
		exit;
// 		echo '<h1>'.$error_msg.'</h1>';
// 		exit;
	}
	
	//展现wap版错误页
	function show_wap_error($error_msg = null) {
		if($error_msg == null) {
			$error_msg = $this->error_msg();
		}
		ob_start();
		$buffer = $this->CI->template->load('wap/template', 'wap/error', array('error_msg' => $error_msg), true);
		ob_end_clean();
		echo $buffer;
		exit;
	}

	//获取错误码对应的错误信息
	function error_msg($error_code = null) {
		$argv = func_get_args();
		if($error_code === null) {
			return $this->_error_msg;
		}
		if(!isset(Err::$arErrCode[$error_code])) {
			$argv[0] = '未定义的错误码：['.$error_code.']';
		} else {
			$argv[0] = Err::$arErrCode[$error_code];
		}
		return call_user_func_array('sprintf', $argv);
	}

	//获取所有错误码列表
	function error_list() {
		return Err::$arErrCode;
	}
}