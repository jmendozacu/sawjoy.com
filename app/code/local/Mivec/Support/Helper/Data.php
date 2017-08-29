<?php
class Mivec_Support_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function formText($_name , $_value , $_class = 'input-text')
    {
        $str = "<label id='$_name'><input type='text' name='$_name' value='$_value' class='$_class' />";
        return $str;
    }

    public function formSelect($_name, $_data , $_value = '' , $_required = false)
    {
        $_required = $_required == true ? "required-entry" : '';

        $str = "<label id='$_name' class='$_required'><select id='$_name' name='$_name'><option value=0> -- Please Select -- </option>";

        foreach ($_data as $key => $d) {
            $str .= "<option value='$key'";
            if ($key == $_value) {
                $str .= ' selected="selected"';
            }
            $str .= ">$d</option>\r";
        }
        $str .= "</select><label>";
        return $str;
    }
}