<?php
include_once(APPPATH."models/fields/field_relate_simple_id.php");
class Field_turnover_typ extends Field_relate_simple_id {
    public $where = array();
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_related";
        $this->tableName = '';
        $this->valueField = '';
        $this->showField = '';
        $this->whereData = array();
        $this->valueSetted = false;
        $this->showValue = ' - ';
        $this->enum = array();
        $this->typs = array();
        $this->tableName = 'fTurnoverType';
        $this->valueField = 'id';
        $this->showField = 'name';
        $this->typ = -1;
    }

    public function gen_list_html(){
        return $this->showValue;
    }

    public function setTyp($typ){
        $this->typ = $typ;
        if ($this->typ>-1){
            $this->add_where(WHERE_TYPE_WHERE,'typ',$this->typ);
        }
        
    }

    public function add_where($typ,$name,$data){
        $this->whereData[$name] = array('typ'=>$typ,'data'=>$data);
    }
    public function setOrgId($orgId){
        parent::setOrgId($orgId);
        $this->whereOrgId = $orgId;
    }
    
    private function setEnum(){
        $this->CI->db->select("id,name,typ")
            ->from($this->tableName);
        $this->checkWhere();

        $this->CI->db->order_by('id','asc');
        $query = $this->CI->db->get();

        $this->enum[0] = ' - ';
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this->enum[$row[$this->valueField]] = $row[$this->showField];
                $this->typs[$row['id']] = $row['typ'];
            }
            
        } 
        
    }
    
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        $this->setEnum();
        if ($typ==1){
            $this->default = $this->value;
        } else {
            $this->default = $default;
        }
        if ($typ!=2){
            $plus = "var valueTyps = ".json_encode($this->typs).";";
            if ($typ==1){
                $plus .= "turnoverInputTypShow(1);";
            }
            $onchangeplus = "onchange='turnoverInputTypShow({$typ})'";
        } else {
            $plus = "";
            $onchangeplus = "";
        }
        $editor = "<script>{$plus}</script><select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" value=\"{$this->default}\" $validates $onchangeplus> ";

        foreach ($this->enum as $key => $value) {
            $editor.= "<option ". (($key==$this->default)?'selected="selected"':'') ." value=\"$key\">$value</option>";
        }
        $editor .= "</select>";
        return $editor;
    }
}
?>