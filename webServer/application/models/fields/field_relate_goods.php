<?php
include_once(APPPATH."models/fields/field_related_id.php");
class Field_relate_goods extends Field_related_id {
    public $where = array();

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->set_relate_db('gGoods','_id','name');
        $this->setEditor('store','searchGoods');
        $this->setPlusCreateData(array('updateTS'=>time()));
        $this->searchPlus = "";
    }

    //$crmTyp: 'send','noSend','all'

    public function setTyp($crmTyp){
    	$this->searchPlus = "/".$crmTyp;

    }

}
?>
