<?php
include_once(APPPATH."models/fields/field_string.php");

class Field_showurl extends Field_string {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_showurl";
    }
    public function init($value){
        parent::init($value);
    }
}
?>