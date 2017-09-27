<?php
require 'config.php';
require 'auth.php';

$_country = trim(@$_GET['country']);
//分页
$curPage = 1;
if (isset($_GET['page'])) {
    $curPage = $_GET['page'];
}

$sql = "SELECT * FROM " . __TABLE_COUNTRY__;

if (!empty($_country)) $sql .= " WHERE country LIKE '%$_country%'";
$sql .= " ORDER BY id DESC";

$config['sql'] = $sql;
$config['limit'] = 20;
$result = pager($config , $curPage);
$row = $result['row'];
//print_r($result);exit;

?>
<?php include 'header.php';?>
<title>Country List</title>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="list_row" style="background:#e8e8e8">
    <tr>
        <td height="14" colspan="4" bgcolor="#FFFFFF">Country List</td>
    </tr>
    <tr>
      <td height="15" colspan="4" bgcolor="#FFFFFF">
      <form name="country" action="?" method="get">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td width="8%" height="25">Country</td>
              <td width="92%"><?php echo formText('country' , $_country);?></td>
            </tr>
          </table>
          </form>
          </td>
        </tr>
        <tr>
          <td colspan="3"><button id="submit" type="submit" class="button btn-cart"> <span><span>Filter</span></span></button>
            <button id="reset" type="button" class="button btn-cart" onclick="window.location.href='data.php'" value="Reset"> <span><span>Reset</span></span></button></td>
        </tr>
      </table></td>
    </tr>
    <tr>
        <td height="25" colspan="4" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
    <tr>
        <td width="6%" height="25">ID</td>
        <td width="28%">Name</td>
        <td width="42%">ISO Code</td>
        <td width="24%">Action</td>
    </tr>
    <?php
    $i = 0;
    $color = '#FFFFFF';
    foreach ($row as $rs) :
        if ($i % 2 == 0) {
            $color = "#F0FFF0";
        } else {
            $color = '#FFFFFF';
        }
        ?>
        <tr class="data" bgcolor="<?php echo $color?>">
            <td height="25"><label>
                    <input type="checkbox" name="id" value="<?php echo $rs['id']?>"  />
                    <?php echo $rs['id']?></label></td>
            <td height="30"><?php echo $rs['country']?></td>
            <td height="30"><?php echo $rs['iso']?></td>
            <td>&nbsp;</td>
        </tr>
        <?php $i++;endforeach;?>
    <tr>
        <td height="30" colspan="4" bgcolor="#FFFFFF"><?php echo $result['page'];?></td>
    </tr>
</table>