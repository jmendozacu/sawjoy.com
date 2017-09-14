<?php
require dirname(dirname(__FILE__)) . '/include/config.php';

$app = Mage::app();
$config  = Mage::getConfig()->getResourceConnectionConfig('default_setup');

//db
$config = array(
    'dbhost'	=> $config->host,
    'dbport'	=> 3306,
    'dbname'	=> $config->dbname,
    'dbuser'	=> $config->username,
    'dbpass'	=> $config->password
);
$db = new Mivec_Db($config);
//$db = Mage::getSingleton('core/resource')->getConnection('core_read');


$_prefix = "mivec_shipping_";

define("__TABLE_AUTH_USER__" , "admin_user");
define("__TABLE_AUTH_ROLE__" , "admin_user_role");

define("__TABLE_CARRIER__" , $_prefix . 'carrier');
define("__TABLE_COUNTRY__" , $_prefix . "country");
define("__TABLE_EXPRESS_DATA__" , $_prefix . "quote_express");
define("__TABLE_AIRMAIL_DATA__" , $_prefix . "quote_airmail");

$_globalData['currency'] = 'CNY ';
$_globalData['carrier_express'] = getCarrierData("type" , "express");
$_globalData['carrier_airmail'] = getCarrierData("type" , "airmail");
$_globalData['country'] = getCountryData();
//print_r($_globalData);exit;

//get carrier data id=>carrier
function getCarrierData($field = '' , $val = "")
{
	global $db;
	$data = array();
	$sql = "SELECT * FROM " . __TABLE_CARRIER__;
	if (!empty($field)) {
		$sql .= " WHERE $field = ";
		$sql .= intval($val) ? "$val" : "'$val'";
	}
	$sql .= " ORDER BY carrier_id DESC";
	if ($row = $db->fetchAll($sql)) {
		foreach ($row as $rs) {
			$data[$rs['carrier_id']] = $rs['carrier_name'];
		}
	}
		
	return $data;
}

//get country data
function getCountryData($field = "" , $val = "")
{
	global $db;
	$data = array();
	$sql = "SELECT * FROM " . __TABLE_COUNTRY__;
	if (!empty($field)) {
		$sql .= " WHERE $field ";
		$sql .= intval($val) ? " =$val" : " LIKE '%$val%'";
		$data = $db->fetch($sql);
	} else {
		if ($row = $db->fetchAll($sql)) {
			foreach ($row as $rs) {
				$data[$rs['id']] = $rs['country'];
			}
			return $data;
		}
	}
	return $data;
}

function getCarrierType()
{
	return array(
		'express'	=> 'Express',
		'airmail'	=> 'Airmail'
	);
}