<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends P_Controller {
	function __construct() {
		parent::__construct(false);
		$this->login_verify();
	}

	function index() {

	}

	function write() {
		$this->login_verify(true);

		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'blog';
        $this->createUrlF = 'doCreateBlog';

        $this->load->model('records/blog_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->editor_typ = 0;
        $this->title_create = "新建商户";
		$this->template->load('default_page', 'blog/write');
	}
	function doCreateBlog(){
		$modelName = 'records/Blog_model';
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

        // $data['comments'] = array();
        $data['commentCount'] = 0;
        // $data['goods'] = array();
        $data['goodCount'] = 0;

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
        //使用 mysql，这里如果需要的话，要初始化赞表和评论表不

        $jsonData = array();
        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('blog/info/'.(string)$newId);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function info($id=''){
    	if ($id==''){
            return;
        }
        $this->login_verify();
        $this->load_menus();
        $this->id = $id;
        $this->load->model('records/Blog_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $this->load->model('records/User_model',"zanUser");
        $this->zanList = array();

        //针对 Mysql 这里单独写
        // foreach ($this->dataInfo->field_list['goods']->value as $this_uid) {
        //     $this->zanUser->init_with_id($this_uid);
        //     $this->zanList[] = array('id'=>$this->zanUser->field_list['_id']->toString(),
        //                              'name'=>$this->zanUser->field_list['name']->value);
        // }

        $this->template->load('default_page', 'blog/info');
    }

    function doZan($id){
        $this->login_verify(true);
        $this->load->model('records/Blog_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $data = array();
        if (in_array($this->uid,$this->dataInfo->field_list['goods']->value)){
            $this->load->library("utility");
            $data['goods'] = $this->utility->array_remove($this->uid,$this->dataInfo->field_list['goods']->value);
            $data['goodCount'] = count($data['goods']);
        } else {
            $data['goods'][] = $this->uid;
            $data['goodCount'] = count($data['goods']);
        }
        $this->dataInfo->update_db($data,$id);

        $this->zanList = array();

        foreach ($this->dataInfo->field_list['goods']->value as $this_uid) {
            $this->zanUser->init_with_id($this_uid);
            $this->zanList[] = array('id'=>$this->zanUser->field_list['_id']->toString(),
                                     'name'=>$this->zanUser->field_list['name']->value);
        }
        $this->zanList[] = array('id'=>$this->uid,'name'=>$this->userInfo->field_list['name']->value);
        $jsonData['dataCount'] = $data['goodCount'];
        $jsonData['data'] = $this->zanList;

        echo $this->exportData($jsonData,1);
    }

    function doComment($id){
        $this->login_verify(true);
        $this->load->model('records/Blog_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $comment = $this->input->post('comment');

        $this->dataInfo->field_list['comments']->addNewLine($comment);
        
        $jsonData['dataCount'] = $data['goodCount'];
        $jsonData['data'] = $this->zanList;

        echo $this->exportData($jsonData,1);
    }
}