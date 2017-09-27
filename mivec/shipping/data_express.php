<?php
require 'config.php';
require 'auth.php';
require 'config.express.php';

//分页
$curPage = 1;
if (isset($_GET['page'])) {
    $curPage = $_GET['page'];
}

$_carrier = @$_GET['carrier'];
$_country = @$_GET['country'];
$_countryIso = @$_GET['countryiso'];

//$sql = "SELECT * FROM " . __TABLE_EXPRESS_DATA__ . " WHERE 1";
$sql = "SELECT 
		A.id,A.carrier_id,A.country_id,A.quote_first,A.quote_add,A.updated_at,B.country as country_name,B.iso ,C.carrier_id,carrier_name
	FROM " . __TABLE_EXPRESS_DATA__ . " A LEFT JOIN " . __TABLE_COUNTRY__ . " B ON(A.country_id=B.id)
		LEFT JOIN " . __TABLE_CARRIER__ . " C ON (A.carrier_id = C.carrier_id)
	WHERE 1
	"
;

if (!empty($_carrier)) $sql .= " AND A.carrier_id=" . $_carrier;
if (!empty($_country)) $sql .= " AND A.country_id=" . $_country;
if (!empty($_countryIso)) $sql .= " AND B.iso LIKE '%$_countryIso%'";

$sql .= " ORDER BY A.id DESC";
$config['sql'] = $sql;
$config['limit'] = 20;
$result = pager($config , $curPage);
$row = $result['row'];

//echo $result['sql'];exit;

if ($row) {
	$frs = array();
	foreach ($row as $data) {
		$frs[] = array(
			'id'	=> $data['id'],
			'carrier_id'	=> $data['carrier_id'],
			'carrier'	=> $data['carrier_name'],
			//'carrier'	=> $_globalData['carrier'][$data['carrier']],
			'country_id'	=> $data['country'],
			//'country'	=> $_globalData['country'][$data['country']],
			'country'	=> $data['country_name'],
			'country_iso'	=> $data['iso'],
			'price'	=> array(
				'first'	=> $data['quote_first'],
				'added'	=> $data['quote_add'],
			),
			'updated_at'	=> $data['updated_at']
		);
	}
}
?>
<?php include 'header.php';?>
<title>Express Data</title>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list_row" style="background:#e8e8e8">
    <tr>
        <td height="30" colspan="8" bgcolor="#FEFCD8">
        <a href="data_express.php" title="Data List">Express Data List</a>
        <a href="data_express.edit.php?referer=<?php echo urlencode($_SERVER['REQUEST_URI']);?>">Add Data</a>
        <!--a href="data.import.php">Import Data</a-->
        </td>
    </tr>
    <tr>
    	<td colspan="8" bgcolor="#FFFFFF">
        <form id="filter" name="filter" method="get" action="?">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
    	  <tr>
    	    <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
    	      <tr>
    	        <td width="12%" height="25">Carrier</td>
    	        <td width="88%"><?php echo formSelect('carrier' , $_globalData['carrier_express'] , $_carrier)?></td>
  	        </tr>
    	      <tr>
    	        <td height="25">Country</td>
    	        <td><?php echo formSelect('country' , $_globalData['country'] , $_country)?></td>
  	        </tr>
    	      <tr>
    	        <td height="25">Country ISO</td>
    	        <td><?php echo formText('countryiso' , $_countryIso)?></td>
  	        </tr>
  	      </table></td>
  	    </tr>
    	  <tr>
    	    <td colspan="3"><button id="submit" type="submit" class="button btn-cart"> <span><span>Filter</span></span> </button>
    	      <button id="reset" type="button" class="button btn-cart" onclick="window.location.href='data_express.php'" value="Reset"> <span><span>Reset</span></span> </button></td>
  	    </tr>
   	  </table>
      </form></td>
  </tr>
  <?php if ($row):?>
    <tr>
        <td height="25" colspan="8" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
    <tr>
        <td width="7%" height="25">ID</td>
        <td width="8%">Carrier</td>
        <td width="27%">Country</td>
        <td width="6%">ISO</td>
        <td width="13%">Frist</td>
        <td width="15%">Added</td>
        <td width="13%">Updated</td>
        <td width="11%">Action</td>
    </tr>
    <?php
    $i = 0;
    $color = '#FFFFFF';
    foreach ($frs as $rs) :?>
        <tr class="data">
            <td height="25"><label><input type="checkbox" name="id" value="<?php echo $rs['id']?>"  /><?php echo $rs['id']?></label></td>
            <td height="30">
				<?php echo $rs['carrier']?>
            </td>
            <td><?php echo $rs['country']?></td>
            <td><?php echo $rs['country_iso'];?></td>
            <td><?php echo $_globalData['currency'].$rs['price']['first']?></td>
            <td><?php echo $_globalData['currency'].$rs['price']['added']?></td>
            <td><?php echo $rs['updated_at']?></td>
            <td>
                <a href="data_express.edit.php?id=<?php echo $rs['id']?>&referer=<?php echo urlencode($_SERVER['REQUEST_URI']);?>" title="Edit">Edit</a>
                <a href="javascript:;" onclick="deleteData(<?php echo $rs['id'];?>)" title="Delete">Delete</a>
            </td>
        </tr>
        <?php $i++;endforeach;?>
    <tr>
        <td height="30" colspan="8" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
    <?php else:?>
    <tr>
    	<td colspan="8"><div class="error-msg">No records found.</div></td>
    </tr>
    <?php endif;?>
</table>
<script type="text/javascript">
function deleteData(id)
{
	if (confirm("Please Make sure you Will delete this data?")) {
		var url;
		url = "data_express.delete.php?id=" + id;
		window.location.href = url;
		return true;
	}
	return false;
}
</script>