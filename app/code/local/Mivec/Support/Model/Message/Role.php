<?php
class Mivec_Support_Model_Message_Role extends Mage_Core_Model_Abstract
{
    public static function getRole()
    {
        $data = array(
            "customer"  => "Customer",
            'supporter'      => "Supporter"
        );
        return $data;
    }
}