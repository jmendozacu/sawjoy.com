<?php
require 'config.php';
define('__TABLE__' , 'catalog_product_entity_varchar');
define('__ATTRIBUTE_ID__' , 82); //82 = meta title


$sql = "SELECT entity_id,`value` FROM " . __TABLE__ . " WHERE attribute_id =" . __ATTRIBUTE_ID__;

if ($row = $db->fetchAll($sql)) {
    foreach ($row as $rs) {
        if (delete($rs['entity_id'])) {
            echo $rs['entity_id'] . ' successfully delete</p>';
        }
    }
}

function delete($_id)
{
    global $db;

    $sql = "DELETE FROM " . __TABLE__ . " WHERE attribute_id=" . __ATTRIBUTE_ID__ . " AND entity_id = " . $_id;
    return $db->query($sql);
}