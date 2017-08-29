<?php
class Mivec_Support_Model_Ticket_Priority extends Mage_Core_Model_Abstract
{
    public static function getPriority()
    {
        $data = array(
            "low"       => "Low",
            "normal"    => "Normal",
            "urgent"    => "Urgent"
        );
        return $data;
    }
}