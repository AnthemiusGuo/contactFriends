<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/org_model.php");
include_once(APPPATH."models/records/peaple_model.php");
// include_once(APPPATH."models/records/org_model.php");
class Org_list extends List_model {
    public function __construct() {
        parent::__construct('oOrg');
        parent::init("Org_list","Org_model");
    }

    public function load_data_with_typ($typ){
        $this->purge_where();
        $this->add_where(WHERE_TYPE_IN,'typ',$typ);
        $this->load_data_with_where();

    }

    public function load_data_with_apply($uid){
        $this->db->select('orgId,roleId,applyTS,applyResult')
                    ->from("oOrgApply")
                    ->where("uid", $uid)
                    ->order_by('applyResult asc, applyTS desc'); ;

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $orgId = $row['orgId'];
                $this->record_list[$orgId] = new Org_model();
                $this->record_list[$orgId]->init_with_id($row['orgId']);

                $this->record_list[$orgId]->field_list['roleId'] = $this->load->field('Field_enum',"身份","roleId");
                $this->record_list[$orgId]->field_list['roleId']->setEnum(array('未设置','员工','志愿者'));
                $this->record_list[$orgId]->field_list['roleId']->init($row['roleId']);
                $this->record_list[$orgId]->field_list['applyTS'] = $this->load->field('Field_date',"申请时间","applyTS");
                $this->record_list[$orgId]->field_list['applyTS']->init($row['applyTS']);
                $this->record_list[$orgId]->field_list['applyResult'] = $this->load->field('Field_enum',"申请结果","applyResult");
                $this->record_list[$orgId]->field_list['applyResult']->setEnum(array('未审核','拒绝','通过'));
                $this->record_list[$orgId]->field_list['applyResult']->init($row['applyResult']);

            }
        }
        $this->dataModel['roleId'] = $this->load->field('Field_enum',"身份","roleId");
        $this->dataModel['applyTS'] = $this->load->field('Field_date',"申请时间","applyTS");
        $this->dataModel['applyResult'] = $this->load->field('Field_enum',"申请结果","applyResult");

    }
    public function load_data_with_attend($uid){
        $this->db->select('orgId,titleId,departmentId,roleId,attendTS')
                    ->from("pPeaple")
                    ->where("uid", $uid);;

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {

                $this->record_list[$row['orgId']] = new Org_model();
                $this->record_list[$row['orgId']]->init_with_id($row['orgId']);

                $this->record_list[$row['orgId']]->field_list['roleId'] = $this->load->field('Field_relate_simple_id',"身份","roleId",true);
                $this->record_list[$row['orgId']]->field_list['roleId']->set_relate_db('oRole','id','name');
                $this->record_list[$row['orgId']]->field_list['roleId']->init($row['roleId']);

                $this->record_list[$row['orgId']]->field_list['attendTS'] = $this->load->field('Field_date',"加入时间","attendTS");
                $this->record_list[$row['orgId']]->field_list['attendTS']->init($row['attendTS']);
                $this->record_list[$row['orgId']]->field_list['departmentId'] = $this->load->field('Field_relate_simple_id',"部门","departmentId",true);
                $this->record_list[$row['orgId']]->field_list['departmentId']->set_relate_db('pDepartment','id','name');
                $this->record_list[$row['orgId']]->field_list['titleId'] = $this->load->field('Field_relate_simple_id',"职位","titleId",true);
                $this->record_list[$row['orgId']]->field_list['titleId']->set_relate_db('pTitle','id','name');
            }
        }
        $this->dataModel['roleId'] = $this->load->field('Field_enum',"身份","roleId");
        $this->dataModel['attendTS'] = $this->load->field('Field_date',"加入时间","attendTS");
        $this->dataModel['departmentId'] = $this->load->field('Field_int',"部门","roleId");
        $this->dataModel['titleId'] = $this->load->field('Field_int',"职位","attendTS");

    }

    public function load_data_with_include($email){
         $this->db->select('orgId,roleId,attendTS')
                    ->from("pPeaple")
                    ->where("email", $email)
                    ->where("uid", 0);


        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $orgId = $row['orgId'];
                $this->record_list[$orgId] = new Org_model();
                $this->record_list[$orgId]->init_with_id($row['orgId']);

                $this->record_list[$row['orgId']]->field_list['roleId'] = $this->load->field('Field_enum',"身份","roleId");
                $this->record_list[$row['orgId']]->field_list['roleId']->setEnum(array('未设置','员工','志愿者'));
                $this->record_list[$row['orgId']]->field_list['roleId']->init($row['roleId']);

                $this->record_list[$row['orgId']]->field_list['attendTS'] = $this->load->field('Field_date',"加入时间","attendTS");
                $this->record_list[$row['orgId']]->field_list['attendTS']->init($row['attendTS']);

            }
        }
        $this->dataModel['roleId'] = $this->load->field('Field_enum',"身份","roleId");
        $this->dataModel['attendTS'] = $this->load->field('Field_date',"加入时间","attendTS");

    }

    public function build_search_infos(){
        return array('name','provinceId','status','phone','isVip');
    }
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','provinceId','status','phone','isVip');
    }
}
?>
