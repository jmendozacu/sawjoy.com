<?php
require 'config.php';
require 'auth.php';

//分页
$curPage = 1;
if (isset($_GET['page'])) {
    $curPage = $_GET['page'];
}

$sql = "SELECT * FROM " . __TABLE_CARRIER__;
$sql .= " ORDER BY carrier_id DESC";

$config['sql'] = $sql;
$config['limit'] = 10;
$result = pager($config , $curPage);
$row = $result['row'];

//print_r($result);
?>
<?php include 'header.php';?>

<title>Carrier List</title>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list_row" style="background:#e8e8e8">
    <tr>
        <td height="30" colspan="8" bgcolor="#FEFCD8">
        	<a href="carrier.php">Cariier List</a>
            <a href="carrier.edit.php">Add Carrier</a>
        </td>
    </tr>
    <tr>
        <td height="25" colspan="8" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
    <tr>
        <td width="5%" height="25">ID</td>
        <td width="24%">Name</td>
        <td width="13%">Type</td>
        <td width="11%">TAX fee</td>
        <td width="10%">Remote</td>
        <td width="10%">Other</td>
        <td width="13%">Updated</td>
        <td width="14%">Action</td>
    </tr>
    <?php
    $i = 0;
    foreach ($row as $rs) :
		$id = $rs['carrier_id'];
		$_deleteUrl = "carrier.delete.php?id=" . $id;
	?>
        <tr class="data">
            <td height="25">
            <label><input type="checkbox" name="id" value="<?php echo $id?>"  /><?php echo $id?></label>
            </td>
            <td height="30">
				<a href="carrier.edit.php?id=<?php echo $id?>" title="edit"><?php echo $rs['carrier_name']?></a>
            </td>
            <td height="30"><?php echo $rs['type']?></td>
            <td height="30"><?php echo $rs['add_tax']?></td>
            <td height="30"><?php echo $rs['add_remote']?></td>
            <td height="30"><?php echo $rs['add_other']?></td>
            <td height="30"><?php echo $rs['updated_at']?></td>
            <td>
                <a href="carrier.edit.php?id=<?php echo $id?>">Edit</a>
                <a href="javascript:;" onclick="deleteData('<?php echo $_deleteUrl;?>')">Delete</a>
            </td>
        </tr>
        <?php $i++;endforeach;?>
    <tr>
        <td height="30" colspan="8" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
</table>
<script type="text/javascript">
function deleteData(_url)
{
	if (confirm("Please Make sure you will delete this data")) {
		window.location.href=_url;
		return true;
	} else {
		return false;
	}
}
</script>