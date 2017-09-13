<?php
require 'config.php';

$act = @$_GET['act'];

$file = dirname(__FILE__) . '/data/data.csv';

$_succeed = FALSE;
if ($content = getCsvContent($file)) {
	foreach ($content as $_key => $_con) {
		$_iso = trim($_con[0]);
		if (!empty($_iso)) {
			//get carrier
			$_carrier = getCarrierDataByKey('carrier' , trim($_con[1]));
			//get country
			$_country = getCountryDataByKey('iso' , $_iso);
			
			$_first = round(trim($_con[2]) , 2);
			$_added = round(trim($_con[3]) , 2);
			
			if (!empty($_country['id'])) {
				$data = array(
					'carrier'	=> $_carrier['id'],
					'country'	=> $_country['id'],
					'first'		=> $_first,
					'added'		=> $_added,
					'created_at'	=> date("Y-m-d")
				);
				//print_r($data);exit;
				
/*				if ($db->insert(__TABLE_EXPRESS_DATA__ , $data)) {
					echo $_country['country'] . "-" . $_con[0] . ' was insert database successfully </p>';
					$_succeed = TRUE;
				}*/
			}
		}
	}
}

//if ($act == 'upload') {
	
	//import freight data
	
//}
?>