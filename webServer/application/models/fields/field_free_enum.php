<?php
include_once(APPPATH."models/fields/Field_string.php");
class Field_free_enum extends Field_string {
    public $enum;
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_enum";
    }
    public function setEnum($enumArray){
        $this->enum = $enumArray;
    }
    public function init($value){
        parent::init($value);
    }
    
    public function gen_show_html(){
        return '<span class="label label-primary">'.$this->value.'</span>';
    }
    public function gen_search_element($default="="){
        return "<input type='hidden' name='searchEle_{$this->name}' id='searchEle_{$this->name}' value='='>=";
    }

    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        if ($typ==1){
            $this->default = $this->value;
        }
        $editor = "<select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" $validates>";
        foreach ($this->enum as $key => $value) {
            $editor.= "<option ". (($key==$this->default)?'selected="selected"':'') ." value=\"$key\">$value</option>";
        }
        $editor .= "</select>";
        return $editor;
    }
}
?>