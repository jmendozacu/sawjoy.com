<?php
require 'config.php';
require 'auth.php';

$action = @$_GET['act'];
$_succeed = @$_GET['succeed'];

if (@$_GET['id']) {
	$id = $_GET['id'];
	$sql = "SELECT * FROM ".__TABLE_CARRIER__ . " WHERE carrier_id=" . $id;
	$rs = $db->fetch($sql);
}

if ($action == 'save') {
	$_url = "carrier.edit.php?succeed=1&id=";
	$id = $_POST['id'];
	$data = array(
		'carrier_name' => $_POST['carrier_name'],
		'add_tax'	=> $_POST['add_tax'],
		'add_remote'	=> $_POST['add_remote'],
		'add_other'		=> $_POST['add_other'],
		'type'		=> $_POST['type'],
		'updated_at'	=> date("Y-m-d")
	);
	
	if (!empty($id)) {
		$db->where("carrier_id=" . $id);
		if ($db->update(__TABLE_CARRIER__ , $data)) {
			$_succeed = TRUE;
			$_url .= $id;
		}
	} else {
		if ($db->insert(__TABLE_CARRIER__ , $data)) {
			$_succeed = TRUE;
			$_url = 'carrier.php';
		}
	}
	
	if ($_succeed) {
		jsLocation("" , $_url);
	}
}
?>
<title>Edit Carrier</title>

<?php include 'header.php';?>
<form id="edit_carrier" method="post" action="?act=save">
<?php if ($_succeed):?>
<div class="success-msg">It was successfully to Saved</div>
<?php endif;?>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list_row" style="background:#e8e8e8">
    <tr>
        <td height="30" colspan="2" bgcolor="#FFFFFF">Edit Carrier</td>
    </tr>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">Carrier Name</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formText('carrier_name' , $rs['carrier_name'])?></td>
    </tr>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">Type</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formSelect('type' , getCarrierType() ,$rs['type'])?></td>
    </tr>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">TAX fee</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formText('add_tax' , $rs['add_tax'])?></td>
    </tr>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">Remote fee</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formText('add_remote' , $rs['add_remote'])?></td>
    </tr>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">Other fee</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formText('add_other' , $rs['add_other'])?></td>
    </tr>
    <?php if (!empty($id)):?>
    <tr>
        <td width="13%" height="30" bgcolor="#FFFFFF">Updated</td>
        <td width="87%" height="30" bgcolor="#FFFFFF"><?php echo formText('updated_at' , $rs['updated_at'])?></td>
    </tr>
    <?php endif;?>
    <tr bgcolor="#F0FFF0">
        <td height="30" colspan="2">
        <input type="hidden" name="id" value="<?php echo $id;?>" />
        <button id="submit" type="submit" class="button btn-cart"> <span><span>Save</span></span> </button>
        <button id="forward" type="button" onclick="window.location.href='carrier.php'" class="button btn-cart">
          <span><span>Back</span></span>
        </button>
        </td>
    </tr>
</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#submit').click(function(){
		//var _content = $('#ticket[content]');
		//$('#edit_carrier').submit();
	});
});
</script>