<?php
require 'config.php';

//set const value for tables
define("_TABLE_PRODUCT_MAIN_" , "catalog_product_entity");
define("_TABLE_PRODUCT_VARCHAR_" , "catalog_product_entity_varchar");
define("_TABLE_PRODUCT_TEXT_" , "catalog_product_entity_text");

//set attribute_id
define("_ATTR_NAME_" , 71);
define("_ATTR_SEARCH_WEIGHT_" , 168); //The Weight Of Search Index

define('_VALUE_WEIGHT_' , 999);

$_keyWords = array("LCD");

$sql = "SELECT a.entity_id,a.`sku`,b.attribute_id,b.`value` FROM "._TABLE_PRODUCT_MAIN_." a LEFT JOIN 
	"._TABLE_PRODUCT_VARCHAR_." b ON(a.entity_id = b.entity_id)
	WHERE attribute_id = " ._ATTR_NAME_. "
";
if (is_array($_keyWords)) {
	foreach ($_keyWords as $_keyword) {
	    $sql .= " AND (`value` LIKE '%$_keyword%') ";
    }
}
$sql.= "ORDER BY a.entity_id DESC";

/*if ($row = $db->fetchAll($sql)) {
	foreach ($row as $rs) {
		$_id = $rs['entity_id'];
		$_sku = $rs['sku'];

		try {
            if (updateIndexWeight($_id)) {
                echo $_sku . " : was updated successfully</p>";
            }
        } catch (exception $e) {
		    echo $e->getCode() . " : " . $e->getMessage();
        }
	}
}*/

function updateIndexWeight($_productId)
{
	global $db;
	$sql = "SELECT COUNT(*) FROM " . _TABLE_PRODUCT_TEXT_
        . " WHERE attribute_id=" . _ATTR_SEARCH_WEIGHT_
        . " AND entity_id=$_productId";

    if ($db->fetchOne($sql)) {
        //Update value if is record exist existed
        $sql = "UPDATE " . _TABLE_PRODUCT_TEXT_ . " SET `value`="
            . _VALUE_WEIGHT_
            . " WHERE attribute_id=" . _ATTR_SEARCH_WEIGHT_
            . " AND entity_id=$_productId";

    } else {
        //Or Create a new record
        $sql = "INSERT INTO " . _TABLE_PRODUCT_TEXT_
            . "(`entity_id` , `attribute_id` , `value`)"
            . " VALUES ($_productId , "._ATTR_SEARCH_WEIGHT_." , "._VALUE_WEIGHT_.")";
    }
	return $db->query($sql);
}


function queryData()
{
    $sql = "SELECT a.entity_id,a.`sku`,b.`value`,c.`value` as weight
            FROM catalog_product_entity a
              LEFT JOIN catalog_product_entity_varchar b ON (a.entity_id=b.entity_id)
              LEFT JOIN catalog_product_entity_text c ON (a.entity_id=c.entity_id)
            WHERE b.attribute_id = 71
              AND c.attribute_id = 168
                  AND b.`value` LIKE \"%LCD%\"
                  AND b.`value` LIKE \"%iphone%\"
            ORDER BY entity_id DESC";
}