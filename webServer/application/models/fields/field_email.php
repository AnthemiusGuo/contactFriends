<?php
include_once(APPPATH."models/fields/field_string.php");

class Field_email extends Field_string {

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_email";
        $this->list_short_len = 30;
    }
    public function init($value){
        parent::init($value);
    }

    public function gen_value($value){
        if(trim($value)=="" || preg_match("/^[0-9a-zA-Z.]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i",$value )){
            return $value;
        }      else{
            $this->CI->display_error("json","邮箱格式不正确");
        }
    }

    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        if ($typ==1){
            $this->default = $this->value;
        }
        return "<input id=\"$inputName\"  name=\"$inputName\" class=\"{$this->input_class}\" placeholder=\"{$this->placeholder}\" type=\"email\" value=\"{$this->default}\" $validates/>";
    }
}
?>
