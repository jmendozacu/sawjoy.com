<?php
require dirname(__FILE__) . '/config.php';

define("__TABLE__" , 'catalog_product_entity_varchar');
define("__ATTRIBUTE_ID__" , 71); // title

$brand = array('iPhone' , 'iPad' , "Samsung" , 'OnePlus' , 'Huawei' , "HTC" , "LG" , "Nokia" , "Sony");


$brand = "Sony";
$sql = "SELECT value_id as id,entity_id,`value` as title FROM ".__TABLE__
	." WHERE attribute_id=" . __ATTRIBUTE_ID__
	." AND `value` LIKE '%".$brand."%'"
	//." AND entity_id=26"
	." ORDER BY id DESC";
	  
$row = $db->fetchAll($sql);
$i = 1;
foreach ($row as $rs) {
	//print_r($rs);
	$_id = $rs['entity_id'];
	$_title = trim($rs['title']);
	
	if (checkPosition($_title , $brand) == 0) {
		echo $i . ":" . $_title . "</p>";
		
		$_title = 'For ' . $_title;
/*		if (updateTitle($_title , $_id)) {
			echo $_id . " Update Success</p>";
		}
		*/
		$i++;
	}
	
}

function checkPosition($_str , $_needle)
{
	return stripos($_str , $_needle);
}

function updateTitle($_title ,$_id)
{
	global $db;
	$sql = "UPDATE " . __TABLE__ . " SET `value`='$_title' WHERE attribute_id=".__ATTRIBUTE_ID__." AND entity_id=$_id";
	return $db->query($sql);
}