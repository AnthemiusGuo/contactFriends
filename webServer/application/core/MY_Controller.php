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
	public $orgList = array();
	public $orgId = 0;
	public $orgName = '';
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

	public function limit_access_by_rule($rule) {


        $targetRule  = array('index'=>'all');

        if ($rule['Project']!=null){
            $targetRule['project'] = array();
            if (in_array('InvoledView', $rule['Project'])){
                $targetRule['project'][] = 'index';
            }
            if (in_array('BaseView', $rule['Project'])){
                $targetRule['project'][] = 'index';
                $targetRule['project'][] = 'analytics';
                if (!isset($targetRule['task'])){
                    $targetRule['task'] = array();
                }
                $targetRule['task'][] = 'index';

                if (!isset($targetRule['schedule'])){
                    $targetRule['schedule'] = array();
                }
                $targetRule['schedule'][] = 'index';

                if (!isset($targetRule['donation'])){
                    $targetRule['donation'] = array();
                }
                $targetRule['donation'][] = 'index';
                // $targetRule['donation'][] = 'analytics';

                if (!isset($targetRule['document'])){
                    $targetRule['document'] = array();
                }
                $targetRule['document'][] = 'index';

                if (!isset($targetRule['crm'])){
                    $targetRule['crm'] = array();
                }
                $targetRule['crm'][] = 'index';
                $targetRule['crm'][] = 'analytics';

            }
            if (in_array('Edit', $rule['Project'])){
                $targetRule['project'][] = 'create';
                if (!isset($targetRule['task'])){
                    $targetRule['task'] = array();
                }

                $targetRule['task'][] = 'create';
                if (!isset($targetRule['schedule'])){
                    $targetRule['schedule'] = array();
                }

                $targetRule['schedule'][] = 'create';
                if (!isset($targetRule['donation'])){
                    $targetRule['donation'] = array();
                }
                $targetRule['donation'][] = 'create';

                if (!isset($targetRule['document'])){
                    $targetRule['document'] = array();
                }
                $targetRule['document'][] = 'create';

                if (!isset($targetRule['crm'])){
                    $targetRule['crm'] = array();
                }
                $targetRule['crm'][] = 'create';
            }
            if (in_array('BugetApprove', $rule['Project'])){

            }

        }
    if ($rule['Finance']!=null){
            $targetRule['finance'] = array();
            if (in_array('Reimbursement', $rule['Finance'])){
                $targetRule['finance'][] = 'viewall_reimbursement';
            }
            if (in_array('ReimbursementEdit', $rule['Finance'])){
                $targetRule['finance'][] = 'new_reimbursement';
                $targetRule['finance'][] = 'check_reimbursement';
            }
            if (in_array('ReimbursementApprove', $rule['Finance'])){
                $targetRule['finance'][] = 'approve_reimbursement';
            }
            if (in_array('ReimbursementAudit', $rule['Finance'])){
                $targetRule['finance'][] = 'approve_reimbursement';
            }
            if (in_array('BaseView', $rule['Finance'])){
                $targetRule['finance'][] = 'cashflow';
                $targetRule['finance'][] = 'analytics';

            }

            if (in_array('TurnoverEdit', $rule['Finance'])){
                $targetRule['finance'][] = 'cashflow';
            }
            if (in_array('TurnoverAudit', $rule['Finance'])){
                $targetRule['finance'][] = 'cashflow';
                $targetRule['finance'][] = 'setting';
            }
        }
        if ($rule['Hr']!=null){
            $targetRule['hr'] = array();
            if (in_array('BaseView', $rule['Hr'])){
                $targetRule['hr'][] = 'index';
                $targetRule['hr'][] = 'analytics';
                $targetRule['hr'][] = 'departments';
                $targetRule['hr'][] = 'titles';
            }
            if (in_array('Edit', $rule['Hr'])){
                $targetRule['hr'][] = 'create';
                $targetRule['hr'][] = 'new_department';
                $targetRule['hr'][] = 'new_title';
            }
        }
        if ($rule['Management']!=null){
            if (in_array('Management', $rule['Management'])){
                $targetRule['management'] = 'all';
            } elseif (in_array('Analytics', $rule['Management'])){
                    $targetRule['management'][] = 'index';
                    $targetRule['management'][] = 'orgInfo';


                    $targetRule['project'][] = 'analytics';
                    $targetRule['hr'][] = 'analytics';
                    $targetRule['crm'][] = 'analytics';
            }
        }

        $this->_limitAccessSort($targetRule);
    }

	public function load_menus(){
		$this->_load_default_menus();
        if ($this->is_login){
            if ($this->userInfo->field_list['orgId']->isEmpty()){
                unset($this->all_menus["crm"]);
                unset($this->all_menus["management"]);
                unset($this->all_menus["store"]);
            }
            if ($this->userInfo->field_list['isAdmin']->toBool()==false){
                unset($this->all_menus["admin"]);
            }
        } else {
            
        }
        
		$this->limit_access_by_rule(array());
        array_unshift($this->title,$this->menus[$this->controller_name]['menu_array'][$this->method_name]['name']);
	}

	private function _load_default_menus(){
		$this->all_menus["index"]=array(
                "menu_array"=>array(
                    "index"=>array(
                        "method"=>"href",
                        "href"=>site_url('index/index'),
                        "name"=>"我的信息",
                        "onclick"=>''
                    ),
                    "call"=>array(
                        "method"=>"href",
                        "href"=>site_url('phone/call'),
                        "name"=>"来电查询",
                        "onclick"=>''
                    ),
                    // "notifies"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('index/notifies'),
                    //     "name"=>"通知",
                    //     "onclick"=>''
                    // ),
                    // "mails"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('index/mails'),
                    //     "name"=>"邮件",
                    //     "onclick"=>''
                    // ),
                    // "settings"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('index/settings'),
                    //     "name"=>"设置",
                    //     "onclick"=>''
                    // ),
                ),
                "default_menu"=>"index",
                "name"=>'个人面板',
                "icon"=>'glyphicon-user',
            );

        $this->all_menus["crm"] = array(
                "menu_array"=>array(
                    "index"=>array(
                        "method"=>"href",
                        "href"=>site_url('crm/index'),
                        "name"=>"客户管理",
                        "onclick"=>''
                    ),


                    "contactList"=>array(
                        "method"=>"href",
                        "href"=>site_url('crm/contactList'),
                        "name"=>"联系记录",
                        "onclick"=>''
                    ),
                    "order"=>array(
                        "method"=>"href",
                        "href"=>site_url('crm/order'),
                        "name"=>"订单管理",
                        "onclick"=>''
                    ),

                    "send"=>array(
                        "method"=>"href",
                        "href"=>site_url('crm/send'),
                        "name"=>"发货管理",
                        "onclick"=>''
                    ),
                    "pay"=>array(
                        "method"=>"href",
                        "href"=>site_url('crm/pay'),
                        "name"=>"打款管理",
                        "onclick"=>''
                    ),
                    // "analytics"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('crm/analytics'),
                    //     "name"=>"客户统计",
                    //     "onclick"=>''
                    // ),
                    // "order_ana"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('crm/order_ana'),
                    //     "name"=>"订单统计",
                    //     "onclick"=>''
                    // ),

                ),
                "default_menu"=>"index",
                "name"=>'客户管理',
                "icon"=>'glyphicon-phone-alt',
            );
        $this->all_menus["management"]=array(
                "menu_array"=>array(
                    "index"=>array(
                        "method"=>"href",
                        "href"=>site_url('management/index'),
                        "name"=>"商户概况",
                        "onclick"=>''
                    ),
                    // "analytics"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('management/analytics'),
                    //     "name"=>"商户统计",
                    //     "onclick"=>''
                    // ),
                    "hr"=>array(
                        "method"=>"href",
                        "href"=>site_url('management/hr'),
                        "name"=>"人员管理",
                        "onclick"=>''
                    ),
                    // "import"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('management/import'),
                    //     "name"=>"数据导入",
                    //     "onclick"=>''
                    // ),
                ),
                "default_menu"=>"index",
                "name"=>'商户管理',
                "icon"=>'glyphicon-home',
            );
        $this->all_menus["store"] = array(
                "menu_array"=>array(
                    "index"=>array(
                        "method"=>"href",
                        "href"=>site_url('store/index'),
                        "name"=>"商品管理",
                        "onclick"=>''
                    ),
                    "inventory"=>array(
                        "method"=>"href",
                        "href"=>site_url('store/inventory'),
                        "name"=>"库存管理",
                        "onclick"=>''
                    ),

                    "category"=>array(
                        "method"=>"href",
                        "href"=>site_url('store/category'),
                        "name"=>"商品分类管理",
                        "onclick"=>''
                    ),
                    // "analytics"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('store/analytics'),
                    //     "name"=>"库存统计",
                    //     "onclick"=>''
                    // )

                ),
                "default_menu"=>"index",
                "name"=>'库存管理',
                "icon"=>'glyphicon-th',
            );

        // $this->all_menus["schedule"]=array(
        //         "menu_array"=>array(
        //             "index"=>array(
        //                 "method"=>"href",
        //                 "href"=>site_url('schedule/index'),
        //                 "name"=>"日程查询",
        //                 "onclick"=>''
        //             ),
        //             "create"=>array(
        //                 "method"=>"onclick",
        //                 "href"=>'',
        //                 "name"=>"新建日程",
        //                 "onclick"=>"lightbox({url:'".site_url('schedule/create')."'})",
        //             )
        //         ),
        //         "default_menu"=>"index",
        //         "name"=>'日程',
        //         "icon"=>'glyphicon-calendar',
        //     );
        // $this->all_menus["finance"] = array(
        //         "menu_array"=>array(
        //             "cashflow"=>array(
        //                 "method"=>"href",
        //                 "href"=>site_url('finance/cashflow'),
        //                 "name"=>"流水",
        //                 "onclick"=>''
        //             ),
        //             "analytics"=>array(
        //                 "method"=>"href",
        //                 "href"=>site_url('finance/analytics'),
        //                 "name"=>"财务统计",
        //                 "onclick"=>'',
        //             ),
        //             "setting"=>array(
        //                 "method"=>"href",
        //                 "href"=>site_url('finance/setting'),
        //                 "name"=>"财务设置",
        //                 "onclick"=>''
        //             ),
        //
        //         ),
        //         "default_menu"=>"index",
        //         "name"=>'财务',
        //         "icon"=>'glyphicon-usd',
        //     );
        //
        //
        // $this->all_menus["document"]= array(
        //         "menu_array"=>array(
        //             "index"=>array(
        //                 "method"=>"href",
        //                 "href"=>site_url('document/index'),
        //                 "name"=>"文档查询",
        //                 "onclick"=>''
        //             ),
        //             "create"=>array(
        //                 "method"=>"onclick",
        //                 "href"=>'',
        //                 "name"=>"新建文档",
        //                 "onclick"=>"lightbox({url:'".site_url('document/create')."'})",
        //             )
        //         ),
        //         "default_menu"=>"index",
        //         "name"=>'文  档',
        //         "icon"=>'glyphicon-paperclip',
        //     );

        $this->all_menus["admin"]=array(
                "menu_array"=>array(
                    "orgs"=>array(
                        "method"=>"href",
                        "href"=>site_url('admin/orgs'),
                        "name"=>"商户管理",
                        "onclick"=>''
                    ),
                    "admins"=>array(
                        "method"=>"href",
                        "href"=>site_url('admin/admins'),
                        "name"=>"管理员",
                        "onclick"=>''
                    ),
                    // "approveReal"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('admin/approveReal'),
                    //     "name"=>"实名认证",
                    //     "onclick"=>''
                    // ),
                    // "role"=>array(
                    //     "method"=>"href",
                    //     "href"=>site_url('admin/role'),
                    //     "name"=>"默认角色设置",
                    //     "onclick"=>''
                    // ),
                ),
                "default_menu"=>"index",
                "name"=>'网站管理',
                "icon"=>'glyphicon-cog',
            );

	}
	private function _limitAccessSort($targetRule){
        $this->menus = array();

        foreach ($this->all_menus as $key => $menus) {
            if (true or isset($targetRule[$key])){
                $value = $targetRule[$key];

                if (true or $value=="all"){
                    $this->menus[$key] = $menus;
                } else {
                    $this->menus[$key] = $menus;
                    $this->menus[$key]['menu_array'] = array();

                    foreach ($menus['menu_array'] as $sub_key => $sub_menu) {
                        if (in_array($sub_key,$value)){
                            $this->menus[$key]['menu_array'][$sub_key] = $sub_menu;
                        }

                    }
                }
            }
        }
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

    function load_org_info($force_check = true) {
        if (!$this->is_login) {
            return;
        }
        if ($this->userInfo->field_list['orgId']->isEmpty() || $this->userInfo->field_list['orgId']->value_checked<=0) {
            if ($force_check){
                header("Location:".site_url('index/index'));
            }
            return;
        }

        $this->load->model('records/org_model',"myOrgInfo");

        $this->myOrgInfo->init_with_id($this->userInfo->field_list['orgId']->value);
        $this->isVip = $this->myOrgInfo->field_list['isVip']->toBool();


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
                $this->myOrgId = $this->userInfo->field_list['orgId']->value;

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
