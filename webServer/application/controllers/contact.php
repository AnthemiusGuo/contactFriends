<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends P_Controller {
	function __construct() {
		parent::__construct(false);
		$this->login_verify();
	}

    function index() {

    }

	function lists($searchInfo=""){
        $this->login_verify(true);
        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/User_list',"listInfo");

        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/info/";
        $this->create_link =  $this->controller_name . "/createUser/";
        $this->deleteCtrl = 'contact';
        $this->deleteMethod = 'doDeleteUser';

        if ($this->userInfo->field_list['typ']->value==0){
            $this->canEdit = false;
        }
        $this->template->load('default_page', 'common/list_view');
    }

	function createUser() {
		$this->login_verify(true);

		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'contact';
        $this->createUrlF = 'doCreateUser';

        $this->load->model('records/user_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->editor_typ = 0;
        $this->title_create = "新建商户";
		$this->template->load('default_page', 'common/create');
	}
	function doCreateUser(){
		$modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataInfo");
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $data = array();
        foreach ($this->createPostFields as $value) {
            $data[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
        }

        $data['orgId'] = $this->myOrgId;

        $data['postTS'] = $zeit;
        $data['editTS'] = $zeit;
        $data['postUser'] = $this->userInfo->uid;

        $data['createUid'] = $this->userInfo->uid;
        $data['createTS'] = $zeit;
        $data['lastModifyUid'] = $this->userInfo->uid;
        $data['lastModifyTS'] = $zeit;
        $checkRst = $this->dataInfo->check_data($data);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataInfo->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $newId = $this->dataInfo->insert_db($data);

        $jsonData = array();
        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('blog/info/'.(string)$newId);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function info($id){
    	if ($id==0){
            return;
        }
        $this->login_verify();
        $this->load_menus();

        $this->id = $id;

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $this->load->model('lists/Blog_list',"listInfo");

        $this->listInfo->load_data_with_foreign_key("postUser",$id);

        $this->template->load('default_page', 'user/info');
    }
}