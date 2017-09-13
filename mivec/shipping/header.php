<link href="res/css/styles.css" rel="stylesheet" />
<script type="text/javascript" src="res/js/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="res/js/mivec/common.js"></script>
<style>
    body          {	 }
    a {color:#39C}
    a:hover{text-decoration:underline}

    .top td{padding:5px 5px 2px 5px;}
    .top a,.list_row a {padding-right:5px;text-decoration:none}
    .top a:hover{color:#fff;background:#099; text-decoration:underline}

    /* CSS Document */
    table {font-size:14px;}
    /* word-break:break-all; word-wrap:break-word; */
    .list_row {margin:5px 0;}
    /*.list_row td {padding:2px;}*/
    .list_row td {padding:5px 5px 2px 5px;}
    .list_row a{ text-decoration:none}
    .list_row a:hover{color:#069}

    .break {word-break:break-all; word-wrap:break-word;}

    .important {font-size:16px;font-weight:bold;color:#F00;display:block}


	.height{ 
	  background:#FAF7B4 !important;   /*背景颜色为灰色*/ 
	} 
	tr{ 
		cursor: pointer;    /*手形*/   
	}

    select {
        margin: 2px;
        padding: 3px;
        text-overflow: ellipsis;
    }
    input.input-text, textarea {
        padding: 5px;
        text-overflow: ellipsis;
    }

    .notice-msg {
        background-position:25px center !important;
        background-repeat:no-repeat !important;
        padding:10px 20px 10px 80px !important;
        font-size:12px !important;
    }

    pager .amount, .pager .limiter, .pager .pages, .sorter .amount, .sorter .limiter, .sorter .view-mode, .sorter .sort-by {
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .pager .pages {
        float: right;
        vertical-align: middle;
    }
    caption, th, td {
        font-weight: normal;
        text-align: left;
        vertical-align: top;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
	
	//$("tr.data:odd").css("background-color","#F0FFF0");
	$("tr.data:even").css("background-color","#F0FFF0");
	
	$("tr.data").mouseover(function(){
		$(this).addClass("height").siblings().removeClass("height");
	});
})
</script>
<?php
$navi = array(
    'Main' => array(
        'carrier.php'   => 'Carrier' ,
        'country.php'   => 'Country',
        'data_express.php'       => 'Express Data',
		'data_airmail.php'       => 'Airmail Data',
		'Soap_Client.php'	=> 'Check Data',
        //'purchase.php'      => '配货单'
    ),
);

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="top" style="margin-bottom:10px;background:#e8e8e8">
    <!--tr>
        <td height="25" bgcolor="#999999">Login as
            <?php //$userInfo = $acl->getUserinfo(); echo $userInfo['user']?></td>
        <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
    </tr-->
    <?php foreach ($navi as $_naviHeader    => $_naviVal):?>
        <tr>
            <td height="30" bgcolor="#E8E8E8">
              <?php foreach ($_naviVal as $_url   => $_title):?>
              <a href="<?php echo $_url;//strtolower($_naviHeader) ."/" . $_url?>"><?php echo $_title?></a>
              <?php endforeach;?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
