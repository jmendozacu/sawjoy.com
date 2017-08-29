<?php
require 'config.php';

$sql = "SELECT * FROM " . __TABLE_PRODUCT__ . " ORDER BY entity_id DESC";
if ($row = $db->fetchAll($sql)) {
	foreach ($row as $rs) {
		$_id = $rs['entity_id'];
		$sql = "UPDATE ".__TABLE_PRICE__ . " SET `value`=`value`/".__CFG_CURRENCY__ . " WHERE entity_id=$_id AND attribute_id=".__ATTR_PRICE__;
		echo $sql;exit;
		
	}
}