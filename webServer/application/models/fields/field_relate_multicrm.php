<?php
include_once(APPPATH."models/fields/field_related_multi_ids.php");
class Field_relate_multicrm extends Field_related_multi_ids {
    public $where = array();
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->set_relate_db('cCrm','id','name');
        $this->setEditor('crm','searchCrm/');
        $this->setPlusCreateData(array('typ'=>0));
        $this->myOrgDft = false;
    }

    public function setMyOrgDft($dft){
        $this->myOrgDft = $dft;
        $this->setEditor('crm','searchCrmDft/');
    }

}
?>