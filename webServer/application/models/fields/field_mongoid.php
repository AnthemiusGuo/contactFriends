<?php
include_once(APPPATH."models/fields/fields.php");
class Field_mongoid extends Fields {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_mongoid";
        $this->baseTyp = "Field_id";
    }
    public function init($value){
        parent::init($value);
    }
    public function gen_value($input){
        if (DB_TYPE=="MYSQL"){
            $input = $input;
        } else {
            $input = $input->{'$id'};
        }
        
    
        return $input;
    }
    public function build_validator(){
        
    }
    public function gen_editor($typ=0){
        
    }
    public function check_data_input($input)
    {
        if ($input===0){
            return false;
        }
        return parent::check_data_input($input);
    }
}
?>