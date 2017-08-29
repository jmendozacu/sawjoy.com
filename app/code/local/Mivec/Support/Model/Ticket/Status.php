<?php
class Mivec_Support_Model_Ticket_Status extends Mage_Core_Model_Abstract
{
    public static function getStatus()
    {
        $data = array(
            1    => "Open",
            2    => "Closed",
            3    => "Wait Customer Reply"
        );
        return $data;
    }
}