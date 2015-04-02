<?php
include_once(APPPATH."models/fields/fields.php");

class Field_array extends Fields {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_array";
    }
    public function init($value){
        parent::init((int)$value);
    }
    public function build_validator(){
        $validater = ' digits ';
        if ($this->is_must_input){
            $validater .= ' min="1" ';
        }
        $validater .= parent::build_validator();
        return $validater;
    }

    public function gen_search_result_show($value){
        return $this->enum[$value];
    }
    public function setEnum($enumArray){
        $this->enum = $enumArray;
    }
    public function gen_search_element($default="like"){
        return "<input type='hidden' name='searchEle_{$this->name}' id='searchEle_{$this->name}' value='like'>包含";
    }

    public function gen_list_html(){
        $json_value = json_decode($this->value,true);
        $string = "";
        if ($json_value == NULL) {
            return $string;
        }
        foreach ($json_value as $key => $value) {
            if (isset($this->enum[$value])){
                $real_value = $this->enum[$value];
            } else {
                $real_value = "未知";
            }
            $string .= '<span class="label label-success">'.$real_value.'</span>'."\n";
        }
        return $string;
    }
    public function gen_show_html(){
        $json_value = json_decode($this->value,true);
        $string = "";
        if ($json_value == NULL) {
            return $string;
        }
        foreach ($json_value as $key => $value) {
            if (isset($this->enum[$value])){
                $real_value = $this->enum[$value];
            } else {
                $real_value = "未知";
            }
            $string .= '<span class="label label-success">'.$real_value.'</span>'."\n";
        }
        return $string;
    }

    public function setDefault($default){
        $this->default = json_decode($default,true);
        if ($this->default==false){
            $this->default = array();
        }
    }
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        if ($typ==1){
            $this->default = $this->value;
        }
        return "<input id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" placeholder=\"{$this->placeholder}\" type=\"text\" value=\"{$this->default}\" $validates /> ";
    }
    public function check_data_input($input)
    {
        if ($input==0){
            return false;
        }
        return parent::check_data_input($input);
            $this->default = json_decode($this->value,true);
            if ($this->default==false){
                $this->default = array();
            }
        }
    //     // $string = '<div class="checkbox">';
    //     // if ($typ!=2){
    //     //     $string = "<select multiple id=\"$inputName\" name=\"$inputName\" class=\"{$this->input_class}\" $validates>";
    //     // } else {
    //     //     $string = "<select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" $validates>";
        
    //     // }
        
    //     // foreach ($this->enum as $key => $value) {
            
    //     //     $string .= '<option value="'.$key.'">'.$value.'</option>'."\n";
    //     // }
    //     // $string .= "</select>";
    //     if ($typ==2){
    //         $width = 'width:42%;text-align:left;padding-top:3px;margin-top:5px;';
    //     } else {
    //         $width = "";
    //     }
    //     if ($typ==2){
    //         $string = "<select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" $validates>";
    //         foreach ($this->enum as $key => $value) {
    //             $string.= "<option ". (($key==$this->default)?'selected="selected"':'') ." value=\"$key\">$value</option>";
    //         }
    //         $string .= "</select>";
    //     } else {
    //         foreach ($this->enum as $key => $value) {
    //             if (in_array($key,$this->default)){
    //                 $plus = 'checked="checked"';
    //             } else {
    //                 $plus = "";
    //             }
    //             $string .= '<label class="checkbox-inline" style="'.$width.'"><input type="checkbox" name="'.$inputName.'[]" class="'.$inputName.'" id="'.$inputName.$key.'" value="'.$key.'" '.$plus.'/>'.$value."</label>";
    //         } 
    //     }
        

        
    //     // $string .= "</div>";
    //     return $string;
    // }
    public function gen_value($input){
        

        return json_encode($input);
    }
    public function importData($value){
        $values = explode("|",$value);
        $rst = array();
        foreach ($this->enum as $k => $v) {
            if (in_array($v,$values)){
                //有这个
                $rst[] = $k;
            } 
        }
        return json_encode($rst);
    }
}
?>