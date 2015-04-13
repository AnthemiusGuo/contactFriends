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

        $this->canEdit = true;
        $this->template->load('default_page', 'common/list_view');
    }

	function createUser() {
		$this->login_verify(true);

		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'contact';
        $this->createUrlF = 'doCreateUser';

        $this->load->model('records/user_model',"dataInfo");

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->editor_typ = 0;
        $this->title_create = "新建联系人";
		$this->template->load('default_lightbox_new', 'common/create');
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
        //检查phone 是否重复
        if (isset($data['phone'])){
            if ($this->dataInfo->check_phone_exist($data['phone'])){
                $jsonRst = -3;
                $jsonData = array();
                $jsonData['err']['id'] ='creator_phone';
                $jsonData['err']['msg'] ='这个手机号已经有人使用';
                echo $this->exportData($jsonData,$jsonRst);
                return false;
            }
        }

        $data['regTS'] = $zeit;
        $data['typ'] = 0;

        $data['inviteCode'] = substr(md5($zeit.rand(0,100000)), 5,8);
        
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
		$jsonData['goto_url'] = site_url('contact/info/'.(string)$newId);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function editUser($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'contact';
        $this->createUrlF = 'doUpdateUser';

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();
        


        $this->editor_typ = 1;
        if ($this->uid==$id){
            $this->title_create = "编辑我的信息";
            //自己可以改密码
            $this->createPostFields[] = 'pwd';
            $this->modifyNeedFields[] = array('pwd');
        } else {
            $this->title_create = "编辑联系人信息";
        }
        
        $this->template->load('default_lightbox_edit', 'common/create');
    }

    function doUpdateUser($id){
        $modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

        $this->dataModel->init_with_id($id);
        $this->createPostFields = $this->dataModel->buildChangeNeedFields();

        $data = array();
        foreach ($this->createPostFields as $value) {
            $newValue = $this->dataModel->field_list[$value]->gen_value($this->input->post($value));
            if ($newValue!="".$this->dataModel->field_list[$value]->value){
                $data[$value] = $newValue;
            }
        }

        if (empty($data)){
            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] ='无变化';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $checkRst = $this->dataModel->check_data($data,false);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
        $zeit = time();

        //检查phone 是否重复
        if (isset($data['phone'])){
            if ($this->dataModel->check_phone_exist($data['phone'])){
                $jsonRst = -3;
                $jsonData = array();
                $jsonData['err']['id'] ='modify_phone';
                $jsonData['err']['msg'] ='这个手机号已经有人使用';
                echo $this->exportData($jsonData,$jsonRst);
                return false;
            }
        }
        $this->dataModel->update_db($data,$id);

        $jsonData['goto_url'] = site_url('contact/info/'.$id);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function doDelUser($id){
        $this->load->model('records/User_model',"dataModel");
        $rst = $this->dataModel->init_with_id($id);
        if ($rst==false){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该记录不存在';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }


        if ($this->dataModel->field_list['typ']->value==1){

            $jsonRst = -3;
            $jsonData = array();
            $jsonData['err']['msg'] = "该用户已经创建账户，不可删除";
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $this->dataModel->delete_db($id);

        //剩下的联系人和联系记录数据不检查删除时候是否有记录，直接一起删除掉
        $this->dataModel->delete_related($id);

        $jsonRst = 1;
        $jsonData['goto_url'] = site_url('contact/lists');

        echo $this->exportData($jsonData,$jsonRst);
    }

    function editMy(){
        $id = $this->uid;
        $this->editUser($id);
    }

    function my(){
        $this->login_verify(true);

        $this->id = $this->uid;

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->init_with_id($this->id);

        $this->load->model('lists/Blog_list',"listInfo");

        $this->listInfo->load_data_with_foreign_key("postUser",$this->id);

        $this->template->load('default_page', 'user/my_info');
    }

    function info($id){
    	if ($id==0){
            return;
        }
        $this->login_verify();

        $this->id = $id;

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $this->load->model('lists/Blog_list',"listInfo");

        $this->listInfo->load_data_with_foreign_key("postUser",$id);

        $this->template->load('default_page', 'user/info');
    }
}