<?php
require 'config.php';
require 'auth.php';
require 'config.airmail.php';

$action = @$_GET['act'];
$_succeed = @$_GET['succeed'];

$id = $_GET['id'];
$act = $_GET['act'];

//forward
//$_referer = urldecode($_GET['referer']);
if ($_GET['referer']) {
	$_SESSION['referer'] = urldecode($_GET['referer']);
}

$data = array();
if (!empty($id)) {
	//$sql = "SELECT * FROM " . __TABLE_AIRMAIL_DATA__ . " WHERE id=$id";
	$sql = "SELECT a.*,b.iso FROM " . __TABLE_AIRMAIL_DATA__
		. " a LEFT JOIN " . __TABLE_COUNTRY__ . " b ON (a.country_id = b.id)"
		. " WHERE a.id = " . $id;
	$row = $db->fetch($sql);
}


if ($act == 'save') {
	$_succeed = FALSE;
	$data = $_POST;
	
	//根据ISO找country id
	if (!$data['country_id']) {
		$_country = getCountryData('iso' , $data['iso']);
		$data['country_id'] = $_country['id'];
	}
	
	$data['updated_at'] = date("Y-m-d");
	//update
	if (!empty($id)) {
		if ($db->where("id=" . $id)
			->update(__TABLE_AIRMAIL_DATA__ , $data)){
			$_succeed = TRUE;	
		}
	}
	else {
		if ($db->insert(__TABLE_AIRMAIL_DATA__ , $data)) {
			$_succeed = TRUE;
		}
	}
	
	if (!empty($id)) {
		$_url = "?id=" . $id . "&succeed=" . $_succeed;
	} else {
		$_url = $_SESSION['referer'];
	}
	//echo $_url;
	jsLocation("" , $_url);
}

?>
<?php include 'header.php';?>
<form id="edit_carrier" method="post" action="?act=save&id=<?php echo $id;?>">
<?php if ($_succeed):?>
<div class="success-msg">It was successfully to Saved</div>
<?php endif;?>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list_row" style="background:#e8e8e8">
    <tr>
        <td height="30" colspan="2" bgcolor="#FFFFFF">Add/Edit Freight Data</td>
    </tr>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Select Carrier</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formSelect('carrier_id' , $_globalData['carrier'] , $row['carrier_id'])?></td>
    </tr>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Select Country</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formSelect('country_id' , $_globalData['country'] , $row['country_id'])?></td>
    </tr>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Or Input Country Code</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formText('iso' , $row['iso'])?></td>
    </tr>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Price</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formText('quote' , $row['quote'])?><span class="required">* CNY</span></td>
    </tr>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Tracking Number Fee</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formText('tracking_no' , $row['tracking_no'])?><span class="required">* CNY</span></td>
    </tr>
    <?php if (!empty($id)) :?>
    <tr>
        <td width="17%" height="30" bgcolor="#FFFFFF">Update Date</td>
        <td width="83%" height="30" bgcolor="#FFFFFF"><?php echo formText('updated_at' , $row['updated_at'])?></td>
    </tr>
    <?php endif;?>
    <tr bgcolor="#F0FFF0">
        <td height="30" colspan="2">
        <button id="submit" type="submit" class="button btn-cart"> <span><span>Save</span></span></button>
        <button id="forward" type="button" onclick="window.location.href='data_airmail.php'" class="button btn-cart">
          <span><span>Back</span></span>
        </button>
        </td>
    </tr>
</table>
</form>