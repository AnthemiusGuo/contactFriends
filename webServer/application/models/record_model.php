 <?php
 class Record_model extends CI_Model {
    public $id;
    public $field_list;
    public $tableName;
    public $orgId;
    public $deleteCtrl = '';
    public $deleteMethod = '';
    public $edit_link = '';
    public $info_link = '';
    public $id_is_id = true;//id字段是mongoid对象还是字符串
    public $none_field_data = array();

    public function __construct($tableName='') {

        parent::__construct();
        $CI =& get_instance();
        if (DB_TYPE=="MYSQL"){
            $this->db = $CI->db;
        } else {
            $this->db = $CI->cimongo;
        }
        $this->tableName = $tableName;
        $this->field_list = array();
        $this->orgId = 0;
        $this->errData = '';
        $this->relateTableName = array();

        $this->default_is_lightbox_or_page = true;

    }
    public function init($id){
        $this->id = $id;

    }

    public function gen_url($key_names,$force_lightbox=false,$info_link=''){
        if ($info_link=='') {
            $info_link = $this->info_link;
        }

        if ($info_link==''){
            //报错
        }
        if ($this->default_is_lightbox_or_page) {
            return '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($info_link.'/'.$this->id).'\'})">'.$this->field_list[$key_names]->gen_list_html().'</a>';
        } else {
            return '<a href="'. site_url($info_link.'/'.$this->id).'">'.$this->field_list[$key_names]->gen_list_html().'</a>';
        }
    }



    public function fetchArray(){
        $arrayRst = array();
        foreach ($this->field_list as $key => $value) {
            $arrayRst[$key] = $value->value;
        }
    }
    public function setRelatedOrgId($orgId){
        $this->orgId = $orgId;
        foreach ($this->field_list as $key => $value) {
            $value->setOrgId($orgId);
        }
    }
    public function gen_list_html($templates){

    }
    public function gen_editor(){

    }

    public function buildInfoTitle(){

    }

    public function check_data($data,$strict=true){
        $effect = 0;
        $this->error_field = "";
        foreach ($this->field_list as $key => $value) {
            if ($value->is_must_input){
                if (!isset($data[$key])){
                    if ($strict){
                        $this->error_field = $key;
                        return false;
                    }

                }  elseif ($value->check_data_input($data[$key])==false) {
                    $this->error_field = $key;
                    return false;
                }
            }
        }
        return true;
    }

    public function get_error_field(){
        if (isset($this->error_field)){
            return $this->error_field;
        } else {
            return "";
        }
    }

    public function checkNameExist($name){
        $this->db->select('*')
                    ->from($this->tableName)
                    ->where('name', $name);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function init_with_id($id){
        if (DB_TYPE=="MYSQL"){
            $real_id = $id;
        } else {
            if (!is_object($id) && $this->id_is_id){
                $real_id = new MongoId($id);
            } else {
                $real_id = $id;
            }
        }
        
        $this->db->where(array('_id' => $real_id));
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

    public function init_with_data($id,$data,$isFullInit=true){
        if (DB_TYPE=="MYSQL"){
            $this->id = $id;
        } else {
            $this->id = $id->{'$id'};
        }
        
        $this->data = $data;
        foreach ($data as $key => $value) {
            if (isset($this->field_list[$key])){
                if ($isFullInit) {
                    $this->field_list[$key]->init($value);
                } else {
                    $this->field_list[$key]->baseInit($value);
                }

            } else {
                $this->none_field_data[$key] = $value;
            }
        }
    }

    public function gen_op_view(){

    }
    public function gen_op_edit(){
        return '<a class="btn btn-xs list_op tooltips" onclick="lightbox({size:\'m\',url:\''.site_url($this->edit_link).'/'.$this->id.'\'})" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>';

    }
    public function gen_op_delete(){
        return '<a class="btn btn-xs list_op tooltips" onclick=\'reqDelete("'.$this->deleteCtrl.'","'.$this->deleteMethod.'","'.$this->id.'")\' title="删除"><span class="glyphicon glyphicon-trash"></span></a>';

    }

    public function get_list_ops(){
        $allow_ops = array();

        $allow_ops[] = 'edit';
        $allow_ops[] = 'delete';
        return $allow_ops;
    }

    public function get_info_ops(){
        return array('edit','delete');
    }

    public function gen_list_op(){
        $opList = $this->get_list_ops();
        $strs = array();
        foreach ($opList as $op) {
            $func = "gen_op_".$op;
            $strs[] = $this->$func();
        }
        return implode(" ", $strs);
    }

    public function insert_db($data){
        if (isset($this->field_list['_id']) && $this->field_list['_id']->typ == "Field_mongoid") {
            if (DB_TYPE=="MYSQL"){
            } else {
                if (!isset($data['_id'])) {
                    //补充_id 字段
                    $data['_id'] = new MongoId();
                }
            }
            
        }
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }

    public function delete_db($ids){
        $effect = 0;
        $idArray = explode('-',$ids);
        foreach ($idArray as $id) {
            if (DB_TYPE=="MYSQL"){
                $tmpId = $id;
            } else {
                $tmpId = new MongoId($id);
            }
            $this->db->where(array('_id'=>$tmpId ))->delete($this->tableName);
            $effect += 1;
        }
        return $effect;
    }

    public function delete_related($ids){
        $effect = 0;
        $idArray = explode('-',$ids);
        foreach ($idArray as $id) {
            foreach ($this->relateTableName as $thisTableName){
                $this->db->where(array('crmId'=> $id))->delete($thisTableName);
                $effect += 1;
            }
        }
        return $effect;
    }


     public function update_db($data,$id){
        if (!is_object($id) && $this->id_is_id){
            $real_id = new MongoId($id);
        } else {
            $real_id = $id;
        }

        $this->db->where(array('_id'=>$real_id))->update($this->tableName,$data);
        return true;
    }

    public function genShowId($orgId,$typ){
         $this->db->select('*')
                    ->from('oMaxIds')
                    ->where('orgId', $orgId);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
        }
        else
        {
            $this->db->insert('oMaxIds', array("orgId"=>$orgId));
            $result = array("orgId"=>$orgId);
        }
        //处理年份
        if (!isset($result['lastModifyTs'])){
            $result['lastModifyTs'] = 0;
        }

        $zeit  = time();
        $now_year = date('Y',$zeit);
        $last_modify_year = date('Y',$result['lastModifyTs']);

        if ($now_year > $last_modify_year) {
            $result[$typ] = 0;
        }

        if (!isset($result[$typ])){
            $update[$typ] = 1;
        } else {

            $update[$typ] = $result[$typ]+1;
        }
        $update["lastModifyTs"] = $zeit;
        $this->db->where('orgId', $orgId)->update('oMaxIds',$update);

        return $now_year . sprintf("%06d",$update[$typ]);

    }

    function checkImportDataBase($data,$cfg_field_lists){
        $errorData = array();
        foreach ($data as $key => $value) {
            # code...
            if (!isset($cfg_field_lists[$key])) {
                continue;
            }
            $rst = $this->field_list[$cfg_field_lists[$key]]->checkImportData($value);
            if ($rst<=0) {
                $errorData[$this->field_list[$cfg_field_lists[$key]]->gen_show_name()] = $value;
            }
        }
        return $errorData;
    }

    function checkIdBy($param){
        $this->db->select("id")
            ->from($this->tableName)
            ->where($param);
        // $this->checkWhere();

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            return $result["id"];
        } else {
            return -1;
        }
    }

    function checkWhere(){

    }
    public function buildChangeNeedFields($arr_plus = array()){
        $array = $arr_plus;
        foreach ($this->buildChangeShowFields() as $value) {
            foreach ($value as $v) {
                if ($v=='null'){
                    continue;
                }
                $array[] = $v;
            }
        }
        return $array;
    }


}
?>
