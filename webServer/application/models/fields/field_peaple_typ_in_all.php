<?php
include_once(APPPATH."models/fields/field_enum.php");
class Field_peaple_typ_in_all extends Field_enum {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_peaple_typ_in_all";
        $this->setEnum(array('未设置','员工','志愿者'));
    }

    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        if ($typ==1){
            $this->default = $this->value;
        }
        $editor = "<select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" $validates onchange=\"typ_in_all_changed('{$inputName}')\">";
        foreach ($this->enum as $key => $value) {
            $editor.= "<option ". (($key==$this->default)?'selected="selected"':'') ." value=\"$key\">$value</option>";
        }
        $editor .= "</select>";
        return $editor;
    }

}
?>