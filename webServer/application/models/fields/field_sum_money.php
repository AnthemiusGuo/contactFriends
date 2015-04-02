<?php
include_once(APPPATH."models/fields/field_money.php");
class Field_sum_money extends Field_money {
	public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_money";
    }
    private function __calc_money(){
    	return number_format($this->value);
    }
    private function __calc_cn_money(){
        if (abs($this->value)<10000){
            return $this->value;
        } else if (abs($this->value)<100000000){
            return ($this->value/10000).'万';
        }
    }

    public function gen_show_html(){
        $v = $this->__calc_cn_money();
        if ($this->value>0){
            $_html = '<span class="text-success">'.$v.'</span>';
        } else if ($this->value<0){ 
            $_html = '<span class="text-danger">'.$v.'</span>';
        } else {
            $_html = '<span class="text-muted">'.$v.'</span>';
        }
        return $_html;
    }
    public function gen_list_html($len=16){
        $v = $this->__calc_cn_money();
        if ($this->value>0){
            $_html = '<span class="text-success">'.$v.'</span>';
        } else if ($this->value<0){ 
            $_html = '<span class="text-danger">'.$v.'</span>';
        } else {
            $_html = '<span class="text-muted">'.$v.'</span>';
        }
        return $_html;
    }
}