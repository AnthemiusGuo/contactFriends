<?php
include_once(APPPATH."models/fields/field_related_id.php");
class Field_relate_contactor extends Field_related_id {
    public $where = array();

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->set_relate_db('cContactor','_id','name');
        $this->setEditor('crm','searchContactor');
        $this->searchPlus = "";
    }

    public function setCrmId($crmId){
    	$this->setEditor('crm','searchContactor/'.$crmId);
        $this->setPlusCreateData(array('name'=>'','crmId'=>$crmId));
    }


}
?>
