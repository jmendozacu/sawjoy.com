<?php
require dirname(dirname(__FILE__)) . "/include/config.php";

$app = Mage::app();
$db = Mage::getSingleton('core/resource')
    ->getConnection('core_read');
