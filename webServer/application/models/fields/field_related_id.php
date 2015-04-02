<?php
include_once(APPPATH."models/fields/field_relate_simple_id.php");
class Field_related_id extends Field_relate_simple_id {

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_related_id";
        $this->model_name = '';
        $this->placeholder = '请点击<+>输入';
        $this->jsonValue = array();
        $this->plusCreateData = array();
        $this->searchPlus = "";
        $this->tips = "输入内容可以搜索选择，输入新的内容会自动创建";
    }
    public function setPlusCreateData($data){
        $this->plusCreateData = $data;
    }
    public function setEditor($controller,$method){
        $this->editorController = $controller;
        $this->editorMethod = $method;
    }
    public function gen_search_editor($default=""){
        $this->input_class = "form-control input-sm";
        if ($default!="" && $default!=null) {
            $this->jsonValue = json_decode($default,true);
            var_dump( $this->jsonValue);
            $this->showValue = $this->jsonValue[0]['name'];
            $this->value = $this->jsonValue[0]['id'];
            $this->default = $this->jsonValue[0]['id'];
            if ($this->value>0){
                $this->placeholder = '<span class="label label-primary">'.$this->showValue.'</span>';
            } else {
                $this->placeholder = '请点击<+>输入';
            }

        }
        return $this->gen_editor(2);
    }
    public function init($value){
        parent::init($value);

        $this->jsonValue = array(array('id'=>$value,'name'=>$this->showValue));
        if ($value>0){
            $this->placeholder = '<span class="label label-primary">'.$this->showValue.'</span>';
        } else {
            $this->placeholder = '请点击<+>输入';
        }

    }
    public function plusCreate($input){
        $this->plusCreateData[$this->showField] = $input;
        if (isset($this->whereOrgId)){
            $this->plusCreateData['orgId'] = $this->whereOrgId;
        } else {
            $this->plusCreateData['orgId'] = $this->CI->myOrgId;
        }

        $this->CI->db->insert($this->tableName,$this->plusCreateData);
        return $this->CI->db->insert_id();
    }
    public function gen_value($input){
        //如果为空，返回空
        $input = trim($input);
        if ($input=="" || $input=="-"){
            return "";
        }

        //先检查是不是已经是 mongoid
        if (strlen($input)==24 && MongoId::isValid($input)){
            return $input;
        }

        $this->db->select(array($this->valueField,$this->showField))
            ->where(array($this->showField => $input));
        if (!isset($this->whereOrgId)){
            $this->whereOrgId = $this->CI->myOrgId;
        }
        $this->db->where(array('orgId'=>$this->whereOrgId));

        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $real_id = $result['_id']->{'$id'};

        } else {
            $new_id = $this->plusCreate($input);
            $real_id = $new_id->{'$id'};
        }
        return $real_id;
    }
    public function gen_search_result_id($value){
        $value = json_decode($value,true);
        if (count($value)!=1){
            return 0;
        } else {
            if ($value[0]['id']==-1){
                return -1;
            } else {
                return $value[0]['id'];
            }

        }
    }
    public function gen_search_result_show($value){
        $value = json_decode($value,true);
        if (count($value)!=1){
            return '-';
        } else {
            if ($value[0]['id']==-1){
                return '-';
            } else {
                return $value[0]['name'];
            }

        }
    }
    // public function gen_list_html(){
    //     return $this->value;
    // }
    // public function set_relate_model($modelName,$showField,$editorUrl){
    //     $modelName = strtolower($modelName);

    //     $this->modelName = $modelName;
    //     $this->showField = $showField;
    //     $this->editorUrl = $editorUrl;

    //     $modelFile = APPPATH."models/records/{$modelName}.php";
    //     if ( ! file_exists($modelFile))
    //     {
    //         return false;
    //     }
    //     require_once($modelFile);

    //     $class = ucfirst($modelName);

    //     return $this->dataModel = new $class();
    // }

    // public function gen_show_html(){
    //     return $this->dataModel->field_list[$this->showField]->gen_show_html();
    // }
    public function gen_editor($typ=0){

        if ($typ==1){
            $this->default = $this->showValue;
        }
        $this->editor_typ = $typ;
        $this->CI->editorData = $this;
        if ($typ==2) {
            $editor = $this->CI->load->view('editor/relate_box_search', '', true);
        } else {
            $editor = $this->CI->load->view('editor/relate_box', '', true);
        }

        return $editor;
    }

    public function build_validator(){
        $validater .= Fields::build_validator();
        return $validater;
    }
}
?>
