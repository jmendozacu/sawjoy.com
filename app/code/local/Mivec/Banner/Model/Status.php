<?php
class Mivec_Banner_Model_Status extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    public static function getStatus()
    {
        return array(
            self::STATUS_ENABLED => "Enabled",
            self::STATUS_DISABLED => "Disabled"
        );
    }
}