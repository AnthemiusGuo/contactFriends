<?php
include_once(APPPATH."models/fields/field_text.php");

class Field_rich_text extends Field_text {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_text";
        $this->value = "";
    }
    public function init($value){
        parent::init($value);
    }
    public function gen_list_html($len = 20){
        if (mb_strlen($this->value)>$len) {
            return mb_substr($this->value,0,$len)."...";

        } else {
            return $this->value;

        }
    }
    public function gen_show_html(){
        return $this->value;
    }
    public function gen_editor($typ=0){
        if ($typ==1){
            $this->default = $this->showValue;
        }
        $this->editor_typ = $typ;
        $this->CI->editorData = $this;
        if ($typ==2) {
            $editor = $this->CI->load->view('editor/richtext_search', '', true);
        } else {
            $editor = $this->CI->load->view('editor/richtext', '', true);
        }

        return $editor;
    }
}
?>