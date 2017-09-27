<?php
require 'config.php';
require 'auth.php';

$id = $_GET['id'];
$_url = "carrier.php";
if (!empty($id)) {
	if ($db->where("carrier_id=" . $id)->delete(__TABLE_CARRIER__)) {
		jsLocation("it was successfully to delete" , $_url);
	}
}