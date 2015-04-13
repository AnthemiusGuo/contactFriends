<?php
include_once(APPPATH."models/record_model.php");
class User_model extends Record_model {
    public function __construct() {
        parent::__construct('uUser');
        $this->uname = '';
        $this->uid = 0;

        $this->deleteCtrl = 'contact';
        $this->deleteMethod = 'doDelUser';
        $this->edit_link = 'contact/editUser/';
        $this->info_link = 'contact/info/';
        $this->default_is_lightbox_or_page = false;

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"uid","_id");

        $this->field_list['email'] = $this->load->field('Field_email',"电子邮箱","email");
        $this->field_list['phone'] = $this->load->field('Field_string',"电话","phone",true);
        $this->field_list['qq'] = $this->load->field('Field_string',"QQ","qq");
        $this->field_list['weixin'] = $this->load->field('Field_string',"微信","weixin");

        $this->field_list['regTS'] = $this->load->field('Field_date',"注册时间","regTS");
        $this->field_list['typ'] = $this->load->field('Field_enum',"已注册","typ");
        $this->field_list['typ']->setEnum(array("未注册","已注册"));

        $this->field_list['isAdmin'] = $this->load->field('Field_bool',"超级管理员","isAdmin");
        $this->field_list['pwd'] = $this->load->field('Field_pwd',"密码","pwd");

        $this->field_list['name'] = $this->load->field('Field_title',"姓名","name",true);
        $this->field_list['inviteCode'] = $this->load->field('Field_string',"邀请码","inviteCode");
        $this->field_list['intro'] = $this->load->field('Field_text',"个人介绍","intro");
    }

    public function buildChangeShowFields(){
            return array(
                    array('name'),
                    array('email','phone'),
                    array('qq','weixin'),
                    array('intro'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name','typ'),
                    array('email','phone'),
                    array('regTS'),
                    array('sign'),
                    array('intro'),
                );
    }

    public function get_list_ops(){
        $allow_ops = array();
        if ($this->field_list['typ']->value==0){
            $allow_ops[] = 'edit';
            $allow_ops[] = 'delete';
        }
        
        return $allow_ops;
    }


    public function init_by_uid($uid){
        parent::init($uid);
            $id = $uid;
        
        $this->db->where(array('_id'=>$id));

        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($id,$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }

    public function init_by_phone($phone){
        $this->db->where(array('phone'=>$phone));

        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($result['_id'],$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }


    public function init_with_data($id,$data){
        parent::init_with_data($id,$data);
            $this->uid = $id;
        
        $this->uname = $data['name'];
    }


    public function check_phone_exist($phone){
        $this->db->where(array('phone'=>trim($phone)));
        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function reg_user($input){
        // if ($input['email']!='' && $this->check_email_exist($input['email'])){
        //     return -1;
        // }
        if ($input['phone']!='' && $this->check_phone_exist($input['phone'])){
            return -2;
        }
        $this->createPostFields = $this->buildChangeNeedFields();
        $data = array();
        foreach ($this->createPostFields as $key) {
            if (!isset($input[$key])){
                $input[$key] = "";
            }
            $data[$key] = $this->field_list[$key]->gen_value($input[$key]);
        }
        $data['pwd'] = md5($input['pwd']);

        $data['regTS'] = time();
        $data['typ'] = 1;

        $data['inviteCode'] = substr(md5($zeit.rand(0,100000)), 5,8);
        


        $checkRst = $this->check_data($data);
        if (!$checkRst){
            return -1;
        }
        $insert_ret = $this->insert_db($data);

        if (DB_TYPE=="MYSQL"){
            $uid = $insert_ret;
        } else {
            $uid = $insert_ret->{'$id'};
        }

        $data['uid'] = $uid;
        $data['_id'] = $insert_ret;
        $this->init_with_data($insert_ret,$data);

        $this->uid = $uid;
        return 1;
    }

    public function verify_login($email,$pwd){

        $this->db->where(array('phone'=>$email));

        $query = $this->db->get($this->tableName);

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            if ($result['typ']==0){
                //未注册
                return -3;
            }
            $real_pwd = $result['pwd'];
            if (strtolower(md5($pwd))==strtolower($real_pwd)){


                $this->init_with_data($result['_id'],$result);
                return 1;
            } else {
                return -2;
            }
        }
        else
        {
            return -1;
        }
    }

    public function changePwd($pwd,$pwdNew){

        if (strtolower(md5($pwd))!=strtolower($this->field_list['pwd']->value)){

            return -1;
        }
        $data = array(
           'pwd' => strtolower(md5($pwdNew))
        );

        $this->db->where(array('uid'=>$this->uid));
        $this->db->update('uUser', $data);
        return 1;
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return '用户:'.$this->field_list['name']->gen_show_html().'<small>'.$this->field_list['typ']->gen_show_html().'</small>';
    }


}
?>
