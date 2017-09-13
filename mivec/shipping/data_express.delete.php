<?php
require 'config.php';
require 'auth.php';

$id = $_GET['id'];

if (!$id) {
	die('Access Denied');
}

if ($db->where("id=" . $id)
	->delete(__TABLE_EXPRESS_DATA__)) {
	jsLocation("" , "data_express.php?succeed=1");
}