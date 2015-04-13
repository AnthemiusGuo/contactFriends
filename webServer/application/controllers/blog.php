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
        $this->id = $id;
        $this->load->model('records/Blog_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $this->load->model('lists/zan_list',"zanList");
        $this->zanList->load_data_with_foreign_key("blogId",$id);

        $this->load->model('lists/comment_list',"commentList");
        $this->commentList->load_data_with_foreign_key("blogId",$id);

        $this->template->load('default_page', 'blog/info');
    }

    function doZan($id){
        $this->login_verify(true);
        
        $modelName = 'records/Zan_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataInfo");

        $data['postTS'] = $zeit;
        $data['blogId'] = $id;

        $data['postUid'] = $this->userInfo->uid;

        $newId = $this->dataInfo->insert_db($data);

        //更新计数
        $this->load->model('lists/zan_list',"zanList");
        $this->zanList->load_data_with_foreign_key("blogId",$id);
        $count = count($this->zanList->record_list);

        $count_data['goodCount'] = $count;
        $this->load->model('records/Blog_model',"blogInfo");
        $this->blogInfo->update_db($count_data,$id);

        $jsonData = array();
        $jsonData['goto_url'] = site_url('blog/info/'.$id);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function doComment($id){
        $this->login_verify(true);
        $comment = $this->input->post('comment');

        $this->load->model('records/Comment_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $jsonRst = 1;
        $zeit = time();

        $data['postTS'] = $zeit;
        $data['blogId'] = $id;

        $data['postUid'] = $this->userInfo->uid;
        $data['content'] = $comment;

        $newId = $this->dataInfo->insert_db($data);


        //更新计数
        $this->load->model('lists/comment_list',"commentList");
        $this->commentList->load_data_with_foreign_key("blogId",$id);
        $count = count($this->commentList->record_list);

        $count_data['commentCount'] = $count;
        $this->load->model('records/Blog_model',"blogInfo");
        $this->blogInfo->update_db($count_data,$id);

        $jsonData = array();
        $jsonData['goto_url'] = site_url('blog/info/'.$id);
        echo $this->exportData($jsonData,$jsonRst);
    }
}