<?php
include_once(APPPATH."models/fields/field_related_id.php");
class Field_projectid extends Field_related_id {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->set_relate_db('pProject','id','name');
        $this->setPlusCreateData(array('status'=>0));
    }
    public function init($value){
        parent::init($value);
    }
}
?>