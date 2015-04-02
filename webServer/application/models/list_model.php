<?php

class List_model extends CI_Model {
    public $name;
    public $record_list;
    public $quickSearchWhere;
    public $orderKey = array("_id"=>"desc");


    public function __construct($tableName = '') {

        parent::__construct();
        $CI =& get_instance();
        $this->db = $CI->cimongo;

        $this->tableName = $tableName;
        $this->record_list = array();
        $this->whereData = array();
        $this->whereOrgId = null;
        $this->quickSearchWhere = array("name");
        $this->is_lightbox = true;
    }

    public function setOrgId($orgId) {
        $this->whereOrgId = $orgId;
        foreach ($this->dataModel as $key => $value) {
            $value->setOrgId($orgId);
        }
    }
    public function init($name,$dataModelName){
        $this->name = $name;
        $this->dataModelName = $dataModelName;

        $dataModel = new $dataModelName();

        $this->dataModel = $dataModel->field_list;

    }
    public function init_with_relate_id($relateField,$relateId){

    }

    public function purge_where(){
        $this->whereData = array();
    }
    public function add_where($typ,$name,$data){
        $this->whereData[] = array('typ'=>$typ,'name'=>$name,'data'=>$data);
    }
    public function build_where($typ,$name,$data){

        switch ($typ) {
            case '=':
                $this->add_where(WHERE_TYPE_WHERE,$name,$data);
                break;
            case 'like':
                $this->add_where(WHERE_TYPE_LIKE,$name,$data);
                break;
            case '>':
                $this->add_where(WHERE_TYPE_WHERE_GT,$name,$data);
                break;
            case '<':
                $this->add_where(WHERE_TYPE_WHERE_LT,$name,$data);
                break;
            case '>=':
                $this->add_where(WHERE_TYPE_WHERE_GTE,$name,$data);
                break;
            case '<=':
                $this->add_where(WHERE_TYPE_WHERE_LTE,$name,$data);
                break;
            case '!=':
                $this->add_where(WHERE_TYPE_WHERE_NE,$name,$data);
                break;
            default:
                print("-----------------<br/>");
                var_dump($typ,$name,$data);
                print("-----------------<br/>");
                # code...
                break;
        }
    }

    public function add_quick_search_where($info) {
        $regex = new MongoRegex("/$info/iu");

        $array = array();
        if (count($this->quickSearchWhere)<=0){
            return;
        }
        foreach ($this->quickSearchWhere as $value) {
            $array[] = array($value=>$regex);
        }

        $this->db->where(array('$or'=>$array),true);
    }

    public function load_data_with_search($searchInfo,$limit=0){
        if ($searchInfo['t']=="no") {
            $this->load_data_with_where(0,$limit);
        } elseif ($searchInfo['t']=="quick"){

            $this->add_quick_search_where($searchInfo['i']);

            $this->load_data_with_where(0,$limit);
        } elseif ($searchInfo['t']=="full"){
            foreach ($searchInfo['i'] as $key => $value) {
                $this->build_where($value['e'],$key,$this->dataModel[$key]->gen_search_result_id($value['v']));

            };
            $this->load_data_with_where(0,$limit);
        }
    }

    public function load_data_with_fullSearch($field_name,$where_array,$plus_where = array(),$limit = 5){
        $where_clause = array();


        if ($this->whereOrgId!==null && isset($this->dataModel['orgId'])){
            $where_clause['orgId'] = $this->whereOrgId;
        }
        if (count($plus_where)!=0){
            foreach ($plus_where as $key => $value) {
                $where_clause[$key] = $value;
            }
        }
        if (count($where_array)!=0){
            $where_clause['$or'] = array();
            foreach ($where_array as $value) {
                $value = (string) trim($value);
                $value = quotemeta($value);
                $where_clause['$or'][] = array($field_name => new MongoRegex("/$value/i"));
            }
        }

        $this->db->where($where_clause, TRUE);

        $this->db->order_by($this->orderKey);
        if ($limit>0){
            $this->db->limit($limit);
        }

        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if (is_object($row['_id'])){
                    $id = (string)$row['_id'];
                } else {
                    $id = $row['_id'];
                }

                $this->record_list[$id] = new $this->dataModelName();
                $this->record_list[$id]->orgId = $this->whereOrgId;
                $this->record_list[$id]->init_with_data($row['_id'],$row);
            }
            return $num;
        } else {
            return 0;
        }


    }

    public function load_data_with_orignal_where($where_array=array(),$limit=0){

        $this->db->where($where_array, TRUE);

        $this->db->order_by($this->orderKey);
        if ($limit>0){
            $this->db->limit($limit);
        }

        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if (is_object($row['_id'])){
                    $id = (string)$row['_id'];
                } else {
                    $id = $row['_id'];
                }

                $this->record_list[$id] = new $this->dataModelName();
                $this->record_list[$id]->orgId = $this->whereOrgId;
                $this->record_list[$id]->init_with_data($row['_id'],$row);
            }
            return $num;
        } else {
            return 0;
        }


    }

    public function load_data_with_where($where_array=0,$limit=0){
        if ($where_array===0){
            $where_array = $this->whereData;
        }

        foreach ($where_array as $key => $value) {
            $typ = $value['typ'];
            $fieldName = $value['name'];
            $fieldData = $value['data'];

            switch ($typ) {
                case WHERE_TYPE_WHERE:
                    $this->db->where(array($fieldName=>$fieldData));
                    break;
                case WHERE_TYPE_WHERE_GT:
                    $this->db->where_gt($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_WHERE_GTE:
                    $this->db->where_gte($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_WHERE_LT:
                    $this->db->where_lt($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_WHERE_LTE:
                    $this->db->where_lte($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_WHERE_NE:
                    $this->db->where_ne($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_IN:
                    $this->db->where_in($fieldName,$fieldData);
                    break;
                case WHERE_TYPE_LIKE:
                    $this->db->like($key, $value['data'],'iu');
                    break;
            }
        }
        if ($this->whereOrgId!==null && isset($this->dataModel['orgId'])){
            $this->db->where(array('orgId'=>$this->whereOrgId));
        }

        $this->db->order_by($this->orderKey);
        if ($limit>0){
            $this->db->limit($limit);
        }
        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if (is_object($row['_id'])){
                    $id = (string)$row['_id'];
                } else {
                    $id = $row['_id'];
                }

                $this->record_list[$id] = new $this->dataModelName();
                $this->record_list[$id]->orgId = $this->whereOrgId;
                $this->record_list[$id]->init_with_data($row['_id'],$row);
            }
            return $num;
        } else {
            return 0;
        }


    }

    public function load_data($limit=0){
        $this->purge_where();
        $this->load_data_with_where(0,$limit);
    }

    public function load_data_with_foreign_key($keyName,$keyValue,$limit=0){
        $this->purge_where();
        $this->add_where(WHERE_TYPE_WHERE,$keyName,$keyValue);
        $this->load_data_with_where(0,$limit);
    }

    public function load_data_with_data($data,$dataModelName){
        foreach ($data as $row)
        {
            if (is_object($row['_id'])){
                $id = (string)$row['_id'];
            } else {
                $id = $row['_id'];
            }

            $this->record_list[$id] = new $dataModelName();
            $this->record_list[$id]->orgId = $this->whereOrgId;
            $this->record_list[$id]->init_with_data($row['_id'],$row);
        }
    }
}
?>
