<?php
//fedex 的价上浮10%
require 'config.php';

$_dhl = 1;
$_rate = 1.1; // 10% percent

$sql = "SELECT * FROM " . __TABLE_EXPRESS_DATA__ . " WHERE carrier=$_dhl";
if ($row = $db->fetchAll($sql)) {
    foreach ($row as $rs) {
        $arr = array(
            'carrier'   => 7, //7=fedex
            'country'   => $rs['country'],
            'first' => round($rs['first'] * $_rate , 2),
            'added' => round($rs['added'] * $_rate , 2),
            'created_at'    => date('Y-m-d')
        );
        //print_r($arr);exit;

/*        if ($db->insert(__TABLE_EXPRESS_DATA__ , $arr)) {
            echo "it was insert successfully</P>";
        }*/
    }
}