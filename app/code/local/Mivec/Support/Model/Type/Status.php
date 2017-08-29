<?php
class Mivec_Support_Model_Type_Status extends Mage_Core_Model_Abstract
{
    public static function getStatus()
    {
        $data = array(
            'enabled'   => 'Enabled',
            'disabled'  => "Disabled"
        );
        return $data;
    }
}