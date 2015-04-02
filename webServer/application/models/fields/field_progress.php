<?php
include_once(APPPATH."models/fields/field_int.php");
class Field_progress extends Field_int {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_progress";
    }
    
    public function init($value){
        parent::init($value);
    }
    public function gen_list_html(){
        if ($this->value>80) {
            $sub_class = "success";
        } elseif ($this->value>60) {
            $sub_class = "info";
        } elseif ($this->value>30) {
            $sub_class = "warning";
        } else{
            $sub_class = "danger";
        }
        return '<div class="progress in_table">
  <div class="progress-bar progress-bar-'.$sub_class.'" role="progressbar" aria-valuenow="'.$this->value.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$this->value.'%">
    <span class="sr-only">'.$this->value.'%</span>
  </div>
</div>';
    }
    public function gen_show_html(){
        if ($this->value>80) {
            $sub_class = "success";
        } elseif ($this->value>60) {
            $sub_class = "info";
        } elseif ($this->value>30) {
            $sub_class = "warning";
        } else{
            $sub_class = "danger";
        }
        return '<div class="progress progress-striped">
  <div class="progress-bar progress-bar-'.$sub_class.'" role="progressbar" aria-valuenow="'.$this->value.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$this->value.'%">
    <span>'.$this->value.'%</span>
  </div>
</div>';
    }
}
?>