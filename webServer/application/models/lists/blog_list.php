<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/blog_model.php");
class Blog_list extends List_model {
    public function __construct() {
        parent::__construct('bBook');
        parent::init("Blog_list","Blog_model");
    }

    public function load_anylatics_with_begin_end($beginTs,$endTS){
        $array = array('totalGetting'=>0);
        if ($this->whereOrgId!==null && isset($this->dataModel['orgId'])){
            $where_clause['orgId'] = $this->whereOrgId;
        }
        $where_clause['beginTS'] = array('$gte'=>$beginTs,'$lte'=>$endTS);
        $this->db->where($where_clause, TRUE)->select(array("totalGetting"));
        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $array['totalGetting'] += $row['totalGetting'];
            }
        }
        return $array;
    }

    public function genAnylytics($typ){
        $this->db->select("COUNT(id) as count_id,typId")
                ->from("dDonation")
                ->where("orgId",$this->whereOrgId)
                ->group_by("pProjectTypRel.typId");
        $real_data = array();

        if ($typ==1){
            $this->db->where_in("status",array(1,2));
        } elseif ($typ==2){
            // $this->db->where_in("status",array(3));
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $real_data[intval($row['typId'])] = intval($row['count_id']);
            }
        }

        $exportData = array();
        foreach ($this->dataModel['typ']->enum as $key => $value) {
            $exportData[] = array("label"=>$value,
                    "data"=>(isset($real_data[$key]))?$real_data[$key]:0
                        );
        }
        return $exportData;
    }

    public function build_search_infos(){
        return array('status','payStatus','beginTS');
    }
    public function build_inline_list_titles(){
        return array('items','status','payStatus','totalGetting','beginTS');
    }
    public function build_short_list_titles(){
        return array('crmId','items','status','payStatus','totalGetting','beginTS');
    }
    public function build_list_titles(){
        return array('crmId','items','status','payStatus','totalGetting','beginTS');
    }
}
?>
