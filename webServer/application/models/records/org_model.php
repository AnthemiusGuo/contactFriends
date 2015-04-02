<?php
include_once(APPPATH."models/record_model.php");
class Org_model extends Record_model {
    public function __construct() {
        parent::__construct('oOrg');
        $this->title_create = '创建商户';
        $this->deleteCtrl = 'admin';
        $this->deleteMethod = 'doDeleteOrg';
        $this->edit_link = 'admin/editOrg/';
        $this->info_link = 'admin/infoOrg/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['showid'] = $this->load->field('Field_showurl',"展示网址","showid");
        $this->field_list['name'] = $this->load->field('Field_title',"商户名称","name",true);
        $this->field_list['provinceId'] = $this->load->field('Field_provinceid',"省份","provinceId");
        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('正常','冻结'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"成立时间","beginTS");
        $this->field_list['addresses'] = $this->load->field('Field_string',"商户地址","addresses");
        $this->field_list['phone'] = $this->load->field('Field_string',"电话","phone");
        $this->field_list['qq'] = $this->load->field('Field_string',"QQ","qq");
        $this->field_list['weixin'] = $this->load->field('Field_string',"微信","weixin");
        $this->field_list['wangwang'] = $this->load->field('Field_string',"旺旺","wangwang");

        $this->field_list['isVip'] = $this->load->field('Field_bool',"VIP","isVip");
        $this->field_list['vipOver'] = $this->load->field('Field_date',"VIP过期时间","vipOver");

        $this->field_list['zipCode'] = $this->load->field('Field_string',"邮编","zipCode");
        $this->field_list['enterCode'] = $this->load->field('Field_string',"加入密码","enterCode");
        $this->field_list['desc'] = $this->load->field('Field_text',"商户介绍","desc");
        $this->field_list['supperUid'] = $this->load->field('Field_userid',"店主","supperUid");
        $this->field_list['commonInviteCode'] = $this->load->field('Field_string',"通用邀请码","commonInviteCode");
        $this->field_list['supperInviteCode'] = $this->load->field('Field_string',"管理员邀请码","supperInviteCode");



        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function isVip(){
        if ($this->field_list['isVip']->toBool()) {
            $zeit = time();
            if ($this->field_list['vipOver']->value<86400){
                //极小值就是永远
                return true;
            }
            if ($zeit<=$this->field_list['vipOver']->value) {
                return true;
            } else {

                return false;
            }
        } else {
            return false;
        }
    }

    public function init_with_org_enterCode($id){
        $this->db->where(array('enterCode' => $id));
        $this->checkWhere();

        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($result['_id'],$result);
            return true;
        } else {
            return false;
        }
    }


    public function gen_all_url(){
        if ($this->isVip() && $this->field_list['showid']->value!='') {
            $id = $this->field_list['showid']->value;
        } else {
            $id = $this->field_list['_id']->toString();
        }
        return array(
                'front'=>site_url('shop/show/'.$id),
                'info'=>site_url('shop/info/'.$id),
                'list'=>site_url('shop/list/'.$id));
    }


    public function gen_front_url(){
        if ($this->isVip() && $this->field_list['showid']->value!='') {
            return site_url('shop/show/'.$this->field_list['showid']->value);
        } else {
            return site_url('shop/show/'.$this->field_list['_id']->toString());
        }
    }

    public function buildInfoTitle(){
        return '商户 :'.$this->field_list['name']->gen_show_html().'&nbsp;&nbsp; <small> ID:'.$this->field_list['provinceId']->gen_show_html().'</small>';
    }

    public function buildShowCardAdmin(){
        $_html = '<div class="shopInfoCard">';
        $url = $this->gen_front_url();
        $_html .= '<h4>['.$this->field_list['provinceId']->gen_show_value().']'.'<a href="'.$url.'" target="_blank">'.$this->field_list['name']->gen_show_html().'</a></h4>';
        if ($this->field_list['beginTS']->value>86400){
            $_html .= '<span class="shopBegin">始于 '.date("Y",$this->field_list['beginTS']->value).' 年</span>';
        }


        $_html .= '<p class="shopDesc">'.$this->field_list['desc']->gen_show_html().'</p>';

        $_html .= '<dl><dt>商户地址</dt>';
        $_html .= '<dd class="dd_wide">'.$this->field_list['addresses']->gen_show_html().'</dd>';
        $_html .= '<dt>商户电话</dt>';
        $_html .= '<dd>'.$this->field_list['phone']->gen_show_html().'</dd>';
        $_html .= '<dt>QQ</dt>';
        $_html .= '<dd>'.$this->field_list['qq']->gen_show_html().'</dd>';
        $_html .= '<dt>微信</dt>';
        $_html .= '<dd>'.$this->field_list['weixin']->gen_show_html().'</dd>';
        $_html .= '<dt>旺旺</dt>';
        $_html .= '<dd>'.$this->field_list['wangwang']->gen_show_html().'</dd>';
        $_html .= '<div class="clearfix"></div></div>';


        return $_html;
    }
    public function buildChangeNeedFields(){
        return array('name','provinceId','desc','addresses','phone','qq','weixin','wangwang');
    }

    public function buildChangeShowFields(){
            return array(
                    array('name'),
                    array('desc'),
                    array('showid'),
                    array('provinceId','null'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name'),
                    array('desc'),
                    array('enterCode'),
                    array('provinceId','null'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),

                );
    }
    public function buildAdminChangeShowFields(){
            return array(
                    array('name'),
                    array('desc'),
                    array('showid'),
                    array('provinceId','zipCode'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),

                );
    }

    public function buildAdminDetailShowFields(){
        return array(
                    array('name','isVip'),
                    array('desc'),
                    array('supperUid'),

                    array('enterCode','status'),
                    array('provinceId','zipCode'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),

                );
    }

    public function init_with_show_id($showId){
        $this->db->where(array('showid' => $showId));
        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($result['_id'],$result);
            return true;
        } else {
            return $this->init_with_id($showId);
        }
    }

    public function get_list_ops(){
        $allow_ops = parent::get_list_ops();
        if ($this->field_list['isVip']->toBool()==false){
            $allow_ops[] = "getvip";
        } else {
            $allow_ops[] = "disvip";
        }
        return $allow_ops;
    }

    public function gen_op_getvip(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("admin","doGetVip","'.$this->id.'")\' title="获得 VIP"><span class="glyphicon glyphicon-usd"></span></a>';

    }

    public function gen_op_disvip(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("admin","doDisVip","'.$this->id.'")\' title="解除 VIP"><span class="glyphicon glyphicon-hand-down"></span></a>';

    }

}
?>
