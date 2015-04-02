<?php
include_once(APPPATH."models/fields/field_tag.php");

class Field_access extends field_string {
    public $showNameModule;
    public $showNameAction;
    public $ruleList;
    public $accessRule;

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_access";
        $this->ruleList = array(
            "Finance"=>array("Reimbursement","ReimbursementEdit","ReimbursementApprove","ReimbursementAudit","BaseView","TurnoverEdit","TurnoverAudit"),
            "Hr"=>array("BaseView","Edit"),
            "Project"=>array("InvoledView","BaseView","Edit","BugetApprove"),
            "Management"=>array("Management","Analytics"),
        );
        $this->showNameModule = array(
            "Finance"=>'财务',
            "Hr"=>'人事',
            "Project"=>'项目/日程/任务/募捐/社会关系/文档',
            "Management"=>'组织管理',
            );
        $this->showNameAction = array(
            "Reimbursement"=>'报销浏览',
            "ReimbursementEdit"=>'报销查询发起',
            "ReimbursementApprove"=>'报销审批',
            "ReimbursementAudit"=>'报销审核',
            "BaseView"=>'浏览查询',
            "InvoledView"=>'概要内容浏览',
            "TurnoverEdit"=>'记账',
            "TurnoverAudit"=>'记账审核',
            "Edit"=>"增删改",
            "BugetApprove"=>'预算审批',
            "Management"=>'组织管理',
            "Analytics"=>'基本信息'
            );
        $this->accessRule = array();
        foreach ($this->ruleList as $moduleName => $value) {
        	$this->accessRule[$moduleName] = array();
        	foreach ($value as $actionName) {
        		$this->accessRule[$moduleName][$actionName] = 0;
        	}
        }
    }


    public function init($value){
        parent::init($value);
        $rules = json_decode($value,true);
        foreach ($this->ruleList as $moduleName => $value) {
        	$this->accessRule[$moduleName] = array();
        	foreach ($value as $actionName) {
        		if (isset($rules[$moduleName])){
        			if (in_array($actionName, $rules[$moduleName])){
        				$this->accessRule[$moduleName][$actionName] = 1;
        			}
        		}
        	}
        }
    }

}
?>