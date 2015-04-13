<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (! defined('__DEFINED_WHERE_TYPE__')) {
    define("__DEFINED_WHERE_TYPE__", "__DEFINED_WHERE_TYPE__");
    define("WHERE_TYPE_WHERE", 0);
    define("WHERE_TYPE_IN", 1);
    define("WHERE_TYPE_LIKE", 2);
    define("WHERE_TYPE_OR_WHERE", 10);
    define("WHERE_TYPE_OR_IN", 11);
    define("WHERE_TYPE_OR_LIKE", 12);
    define("WHERE_TYPE_WHERE_GT", 21);
    define("WHERE_TYPE_WHERE_LT", 22);
    define("WHERE_TYPE_WHERE_GTE", 23);
    define("WHERE_TYPE_WHERE_LTE", 24);
    define("WHERE_TYPE_WHERE_NE", 25);


    define("WHERE_TXT", 99);
}
define("VIEW_TYPE_PAGE", 1);
define("VIEW_TYPE_HTML", 2);
define("VIEW_TYPE_JSON", 3);



class P_Controller extends CI_Controller {
	public $uid;
	public $userInfo;
	public $viewType;
	public $pageClass = 'normal';
	public $menus = array();
    public $need_plus = '';
    public $isVip = false;

	function __construct($login_verify = true) {
		parent::__construct();
		date_default_timezone_set("Asia/Shanghai");

		$this->is_login = false;
        if (DB_TYPE=="MONGO"){
            $this->db = $this->cimongo;
        } else {

        }
        $this->force_lightbox = false;
		$this->load->helper('url');
        $this->controller_name = ($this->uri->segment(1)=="")?'index':$this->uri->segment(1);
        $this->method_name = ($this->uri->segment(2)=="")?'index':$this->uri->segment(2);
        if ($this->method_name =='info'){
            $this->method_name = 'index';
        }
        $this->searchInfo = array('t'=>'no');

        if($login_verify) {
			$this->login_verify();
            $this->canEdit = $this->checkEditRule();
		} else {

		}
        $this->title = array($this->config->item('base_title'));
	}



	function setViewType($viewType){
		$this->viewType =$viewType;
	}
	function checkEditRule(){
		if ($this->controller_name=="crm"){
			return $this->checkActionRule("Crm","Edit");
		} else {
			return $this->checkActionRule("Project","Edit");
		}

	}
	function checkRule($module,$action){
		if ($this->accessRule[$module]!=null){
			if (!in_array($action, $this->accessRule[$module])){
				$this->display_error("no_access");
			}
		} else {
			$this->display_error("no_access");
		}
	}

	function checkActionRule($module,$action){
        return true;
		if ($this->accessRule[$module]!=null){
			if (!in_array($action, $this->accessRule[$module])){
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

	function display_error($error_typ,$error_msg=""){
		$msg_array = array("no_access"=>"您没有权限使用该功能","common"=>"出错啦！");
		if ($error_msg==""){
			$this->error_msg = isset($msg_array[$error_typ])?$msg_array[$error_typ]:$msg_array['common'];
		} else {
			$this->error_msg = $error_msg;
		}
		if ($error_typ=="json" || $this->viewType == VIEW_TYPE_JSON){
			$jsonRst = -1000;
            $jsonData = array();
            $jsonData['err']['msg'] =$this->error_msg;
            echo $this->exportData($jsonData,$jsonRst);
            exit;
		} elseif ($this->viewType == VIEW_TYPE_PAGE) {
			if (!file_exists(APPPATH."views/error/".$error_typ.".php")) {
				$error_typ = "common";
			}

			ob_start();
			$buffer = $this->template->load('default_npo', 'error/'.$error_typ, array(),true);
			ob_end_clean();
			echo $buffer;
		} elseif ($this->viewType == VIEW_TYPE_HTML){
			if (!file_exists(APPPATH."views/error/".$error_typ.".php")) {
				$error_typ = "common";
			}

			ob_start();
			$buffer = $this->template->load('default_lightbox_info', 'error/'.$error_typ, array(),true);
			ob_end_clean();
			echo $buffer;
		}

		exit;
	}


	function buildSearch($searchInfo){
		if ($searchInfo=="") {
			$this->quickSearchValue = "";
			return;
		}
		$this->searchInfo = (json_decode(base64_decode(urldecode($searchInfo)),true));
		if ($this->searchInfo['t']=="quick"){
			$this->quickSearchValue = $this->searchInfo['i'];
		}
	}

	function login_verify($force=false) {
		if($this->login->is_login() === true) {
			$this->is_login = true;
			$this->uid = $this->login->uid;
			$this->load->model('records/user_model',"userInfo");

			$init_result = $this->userInfo->init_by_uid($this->uid);

			if ($init_result<0){
				$this->login->logout();
				if ($force){
                    header("Location:".site_url('index/login'));
                    exit;
                } else {
                    $this->is_login = false;
                    $this->uid = 0;
                }
			} else {

			};
		} else {
            if ($force){
                header("Location:".site_url('index/login'));
                exit;
            } else {
                $this->is_login = false;
                $this->uid = 0;
            }
			
		}
	}

	function login_init() {


	}

	public function genBreadCrumb(){
		return "<ul class='breadcrumb'>
		<li><a href='#'><span class='glyphicon glyphicon-home'></span> Home</a></li>
		<li><a href='#'><span class='glyphicon {$this->Menus->show_menus[$this->controller_name]['icon']}'></span> {$this->Menus->show_menus[$this->controller_name]['name']}</a></li>
		<li class='active'><span class='glyphicon glyphicon-circle-arrow-right'></span> {$this->Menus->show_menus[$this->controller_name]['menu_array'][$this->method_name]['name']}</a></li>
		</ul>";
	}

	function build_request($question_mark = false) {
		$get = $this->input->get();
		if(!$get) {
			return '';
		}
		if($question_mark) {
			return '?'.http_build_query($get);
		}
		return http_build_query($get);
	}


	public function resultEncode($ret)
    {
        return json_encode($ret);
    }

    public function resultDecode($enret)
    {
        return json_decode($enret , true);
    }



    public function exportData($data , $num = 0)
    {
        $ret = array(
            'data' => $data,
            'rstno' => $num,
        );
        return  $this->resultEncode($ret);
    }

    public function checkMenus(){
    	if (empty($this->orgList)){
    		if ($this->userInfo->field_list['isAdmin']->value==1){
				$this->Menus->limit_access("index,admin");

    		} else {
				$this->Menus->limit_access("index");

    		}
		} else {
			$this->Menus->limit_access_by_rule($this->accessRule);
			if ($this->userInfo->field_list['isAdmin']->value==1){
				$key = 'admin';
				$this->Menus->show_menus[$key] = $this->Menus->all_menus[$key];
    		}

		}
    }

    public function sendMail($email,$content,$title){
    	$this->load->library('email');

    	$config['protocol'] = 'smtp';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = false;
		$config['smtp_host'] = '172.18.238.10';
		$config['smtp_user'] = 'webmaster@huopuyun.com';
		$config['smtp_pass'] = 'Abc123';
		$config['smtp_port'] = '25';
		$config['mailtype'] = 'html';

  //   	$config['protocol'] = 'smtp';
		// $config['charset'] = 'utf-8';
		// $config['wordwrap'] = false;
		// $config['smtp_host'] = 'smtp.126.com';
		// $config['smtp_user'] = 'nponechina';
		// $config['smtp_pass'] = 'npone123';
		// $config['smtp_port'] = '25';
		// $config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('nponechina@126.com', 'NPOne平台');
		$this->email->to($email);

		$this->email->subject($title);
		$this->email->message($content);

		$this->email->send();
    }

    public function getPage(){
    	$this->pageNow = $this->input->get('page');
    	if ($this->pageNow===false){
    		$this->pageNow = 0;
    	} else {
    		$this->pageNow = (int)$this->pageNow -1;
    	}
    	if ((int)$this->pageNow<=0){
    		$this->pageNow = 0;
    	}

    }

    public function getSubTab($default){
    	$this->tabNow = $this->input->get('tab');
    	if ($this->tabNow===false){
    		$this->tabNow = $default;
    	}
    }

	public function getSearch(){
    	$this->searchs = $this->input->get('search');
    }
}
