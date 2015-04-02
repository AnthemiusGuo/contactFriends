<?php
include_once(APPPATH."models/fields/field_related_id.php");
class Field_relate_crm extends Field_related_id {
    public $where = array();
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->set_relate_db('cCrm','_id','name');
        $this->setEditor('crm','searchCrm');
        $this->setPlusCreateData(array('typ'=>0,'updateTS'=>time()));
        $this->searchPlus = "";
    }

    //$crmTyp: 'send','noSend','all'

    public function setTyp($crmTyp){
    	$this->searchPlus = "/".$crmTyp;
        
    }

}
?>