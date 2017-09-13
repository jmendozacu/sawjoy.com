<?php
require 'config.php';
require 'auth.php';

$id = $_GET['id'];

if (!$id) {
	die('Access Denied');
}

if ($db->where("id=" . $id)
	->delete(__TABLE_AIRMAIL_DATA__)) {
	jsLocation("" , "data_airmail.php?succeed=1");
}