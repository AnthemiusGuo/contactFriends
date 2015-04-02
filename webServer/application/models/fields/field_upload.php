<?php
include_once(APPPATH."models/fields/field_string.php");

class Field_upload extends Field_string {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_upload";
    }

    public function checkSame($newValue){
        $source = json_decode($this->value,true);
        $target = json_decode($newValue,true);
        if ($target==NULL && $source==NULL) {
            return true;
        }
        if ($target==NULL || $source==NULL) {

            return false;
        }
        if (!isset($target['url']) && !isset($source['url']) ) {

            return true;
        }
        if (!isset($target['url']) || !isset($source['url']) ) {

            return false;
        }
        if ($target['url']==$source['url'] ) {

            return true;
        } else {

            return false;
        }
    }

    public function gen_list_html(){
        $data = json_decode($this->value,true);

        if ($data['is_image']==true) {
            $string = "图片：";
        } else {
            $string = "文档：";
        }
        $string .= "<a href='$data[url]' target='_blank'>$data[client_name]</a>";
        return $string;
    }
    public function gen_show_html(){
        $data = json_decode($this->value,true);
        $string = "下载地址 <a href='$data[url]' target='_blank' >$data[client_name]</a>";
                    
        if ($data['is_image']==true) {
            $string .= "<div><img src=\"$data[url]\" class=\"img-thumbnail\" width=\"100%\"></div>";
        }
        return $string;
    }
    public function gen_value($input){
        return urldecode($input);
    }
}
?>