<?php
include_once(APPPATH."models/fields/field_ts.php");
class Field_date extends Field_ts {

    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_date";

    }
    public function setDefault($default){
        if ($default<=86400) {
            $this->default = ' - ';
        } else {
            if (is_numeric($default)) {
                $this->default = date("Y-m-d",$default);
            } else {
                $this->default = $default;
            }
        }
    }
    public function gen_search_result_id($value){
        return $this->gen_value($value);
    }
    public function gen_js_value(){
        if ($this->value<=0){
            return "-";
        }
        return date("Y-m-d",$this->value);
    }
    public function gen_list_html(){
        if ($this->value<=86400){
            return "-";
        }
        return date("y-m-d",$this->value);
    }
    public function gen_show_html(){
        if ($this->value<=86400){
            return "-";
        }
        return date("Y-m-d",$this->value);
    }
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);

        if ($typ==1){
            if ($this->value<=86400) {
                $this->default = ' - ';
            } else {
                $this->default = date("Y-m-d",$this->value);
            }

        } else {
            if ($this->default<=86400) {
                $this->default = ' - ';
            } else {
                if (is_numeric($this->default)){
                    $this->default = date("Y-m-d",$this->default);
                }
            }

        }

        $validates = $this->build_validator();
        $str = "<input id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" placeholder=\"{$this->placeholder}\" type=\"text\" value=\"".($this->default)."\" $validates />
              <script type=\"text/javascript\">
                $(function(){
                    $(\"#{$inputName}\").datetimepicker({format: 'yyyy-mm-dd',autoclose: true,
        todayBtn: true,language:'zh-CN',minView:'month',startView:'year'});

                });
              </script>";
        return $str;
        // $(\"#{$inputName}\").appendDtpicker({\"inline\": false,\"locale\": \"cn\",\"calendarMouseScroll\": false,\"dateOnly\":true,'dateFormat' : 'YYYY-MM-DD'});
    }
    public function check_data_input($input)
    {
        if ((int)($input) == 0 ) {
            return false;
        }
        return parent::check_data_input($input);
    }

    public function gen_value($input){
        if (trim($input) == "-") {
            return 0;
        }
        $year      =   substr($input,0,4);
        $month       =   substr($input,5,2);
        $day        =  substr($input,8,2);

        return mktime(0,0,0,$month,$day,$year);
    }


}
?>
