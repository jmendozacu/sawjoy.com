<?php
//header('Content-type:text/html;charset=utf-8');
ini_set('display_errors',1);

date_default_timezone_set('HongKong');

define('ROOT',dirname(dirname(dirname(__FILE__))));
define('LIBRARY_PATH',dirname(dirname(dirname(__FILE__))) . '/lib');
define('LIB_MIVEC',dirname(dirname(__FILE__)) . '/lib');

set_include_path(implode(PATH_SEPARATOR, array(
	ROOT,
	LIBRARY_PATH,
	LIB_MIVEC,
    get_include_path(),
)));
//echo get_include_path();exit;

require_once 'app/Mage.php';

require_once 'Mivec/Db.php';
require_once 'Mivec/Acl.php';
require_once 'common.php';

//order
require_once 'order.common.php';

// load PhpMailer lib
require_once 'PhpMailer/class.phpmailer.php';

//media dir
define('MEDIA',ROOT . '/media');
define('MEDIA_PRODUCT',MEDIA . '/catalog/product');

//website
define("__WEB_BASE__" , Mage::getBaseUrl());
define("__WEB_SKIN__" , Mage::getSingleton('core/design_package')->getSkinBaseUrl());

//$db = Mage::getSingleton('core/resource')->getConnection('core_read');