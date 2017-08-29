<?php
require 'config.php';
$_hookHelper = Mage::helper("orderhook");

$orderId = $app->getRequest()->getParam("order_id");
$token = $app->getRequest()->getParam("token");
$pdf = $app->getRequest()->getParam("pdf");
$pdf = empty($pdf) ? "no" : $pdf;

if (!$token ) die("Access Denied");
if ($token != $_hookHelper->encryptToken(array($orderId))) die("Access Denied");

//general config
$_general["name"] = Mage::getStoreConfig("general/store_information/name");
$_general["email"] = Mage::getStoreConfig("contacts/email/recipient_email");
$_general["tel"] = Mage::getStoreConfig("general/store_information/phone");
$_general["business_time"] = Mage::getStoreConfig("general/store_information/hours");
$_general["country"] = "China";
$_general["address"] = Mage::getStoreConfig("general/store_information/address");
//print_r($_general);exit;

$_order = Mage::getModel("sales/order")->load($orderId , "increment_id");
//print_r($_order->getData());exit;

$_orderData = getOrderDetail($_order);
$_orderData["shipping_address"] = $_orderData["shipping_address"]["firstname"]
    . " " . $_orderData["shipping_address"]["lastname"] . "<br>"
    . " " . $_orderData["shipping_address"]["street"]
    . " " . $_orderData["shipping_address"]["city"] . "<br>"
    . " " . $_orderData["shipping_address"]["region"]
    . " " . $_orderData["shipping_address"]["zip"] . "<br>"
    . " " . $_orderData["shipping_address"]["country"] . "<br>"
    . " T:" . $_orderData["shipping_address"]["telephone"];
//print_r($_orderData);exit;

//due date
$date = date("Y-m-d" , strtotime($_order->getData("created_at")));
$_duedate = date("Y-m-d" , strtotime($date) + (86400*15));
$_orderData["due_date"] = $_duedate;

//payment info
$_payment = $_order->getPayment();
$_payAdditional = $_payment->getAdditionalInformation()["instructions"];
$_orderData["payment_info"] = $_payment->getMethodInstance()->getTitle() . "</P>"
    .$_payAdditional;

//print_r(getOredrItems($_order));exit;

//invoice data
$_invoiceData = getOrderInvoice($_order);

function getOrderInvoice(Mage_Sales_Model_Order $order)
{
    $_invoiceData = array();
    if ($order->hasInvoices()) :
        $invoiceCollection = $order->getInvoiceCollection();
        foreach($invoiceCollection as $invoice):
            //var_dump($invoice);
            $_invoiceData = array(
                    "id"    => $invoice->getId(),
                    "increment_id" => $invoice->getIncrementId()
            );
        endforeach;
    endif;
    return $_invoiceData;
}

$_viewUrl = Mage::getUrl("orderhook/invoice/get" , array(
        "token" => $token,
        "order_id"  => $orderId,
        "pdf"   => "yes"
));
$_message = "";
if ($pdf == "yes") {
    $_message = 'You Can Save This Invoice,Click<a id="renderPdf" style="color:blue;padding: 0 10px 0 10px;" href="javascript:void(0)">DOWNLOAD PDF</a> To Get It';
} else {
    $_message = "You Can Download Invoice Below This Link <a href='$_viewUrl'>Download Invoice</a>";
}
?>

<div style="color: #222;background: #fff1a8;border: 1px solid #ccc;height:30px;line-height: 30px;">
    <div style="width:700px;margin:0 auto;padding:0;text-align: right;">
        <p style="margin:0;padding:0;font-size: 14px;"><?php echo $_message?></p></div>
    </div>
</div>

<div id="print" style="background: #f6f6f6;width:700px;margin:0 auto;">
    <table width="700" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" style="font-family:Calibri,Times New Roman,'Helvetica Neue', Helvetica-Neue, HelveticaNeue, Helvetica, Arial, sans-serif;font-size: 10px;">
        <tbody>
        <tr>
            <td>
                <table class="outer" width="650" style="" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff">
                    <!--header-->
                    <tr style="padding: 0 1px;height:100px;">
                        <td width="200" style="vertical-align: top;padding-top: 20px;">
                            <a href="<?php echo __WEB_BASE__?>" style="display: block;">
                                <img src="<?php echo __WEB_SKIN__;?>images/logo.png" alt="Aswanu" width="165" height="48">
                            </a>
                            <!--head-left-->
                            <div style="width:200px;padding:0 10px;font-size:10px;line-height: 16px;">
                                <h5 style="text-transform: uppercase;padding: 25px 0 0 0;margin:0">Ship From</h5>
                                <p style="margin:20px 0 10px 0;">Add : Rm.6409 SEG Plaza Huaqiangbei Rd. Futian Shenzhen P.R.C.</p>
                                <p style="margin: 0 auto;">Tel : <?php echo $_general["tel"]?></p>
                                <p style="margin:0 auto;"><a href="<?php echo __WEB_BASE__?>" style="color:#000;"><?php echo __WEB_BASE__;?></a></p>
                            </div>
                        </td>
                        <td width="100">
                        </td>
                        <!--head-right-->
                        <td width="350" style="padding-top: 20px;vertical-align: top;">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <table>
                                            <tr>
                                                <td width="150" style="vertical-align: top;">
                                                    <div style="font-size: 10px;">
                                                        <p style="margin: 0;font-weight:bold;text-transform: uppercase">Invoice Date</p>
                                                        <p style="margin: 0;"><?php echo $_orderData["general"]["order_date"]?></p>
                                                    </div>
                                                </td>
                                                <td width="200" style="vertical-align: top;">
                                                    <div style="font-size: 10px;">
                                                        <p style="margin: 0;"><span style="display: inline-block;width: 80px;font-weight: bold;text-transform: uppercase;">Invoice:</span><?php echo $_invoiceData["increment_id"]?></p>
                                                        <p style="margin: 0;"><span style="display: inline-block;width: 80px;font-weight: bold;text-transform: uppercase;">Order:</span><?php echo $_orderData["general"]["increment_id"]//$_invoiceData["increment_id"]?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="vertical-align: top;">
                                                    <h5 style="text-transform: uppercase;padding: 36px 0 0 0;margin:0;font-size:12px;">Ship To</h5>
                                                    <div style="font-size: 10px;padding:0 12px 0 0;">
                                                        <p style="line-height:16px;"><?php echo $_orderData["shipping_address"];?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10px;"></td>
                    </tr>
                    <tr>
                        <td width="450" style="vertical-align: bottom">
                        </td>
                        <td></td>
                        <td width="450">
                            <div style="border: 2px solid #000;float:right;width: 200px;font-size:12px;">
                                <p style="font-weight: bold;margin: 0;padding: 4px 10px 20px 10px;text-transform: uppercase">
                                    Total: <span style="font-weight: normal;text-align: right;float: right;text-transform: none"><?php echo $_orderData["amount"]["grand_total"]?></span>
                                </p>
                                <p style="font-weight: bold;margin: 0;padding: 0 10px 4px 10px;text-transform: uppercase">
                                    Due Date : <span style="font-weight: normal;text-align: right;float: right;text-transform: none"><?php echo $_orderData["due_date"]?></span>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <!--order-->
                    <tr>
                        <td colspan="3" align="center">
                        <table cellspacing="0" cellpadding="0" border="0" width="650">
                            <thead>
                                <tr>
                                    <th align="left" style="font-size:13px;padding:3px 9px;text-transform: uppercase;">Sku</th>
                                    <th align="left" style="font-size:13px;padding:3px 9px;text-transform: uppercase;">Item</th>
                                    <th align="center" style="font-size:13px;padding:3px 9px;text-transform: uppercase;">Qty</th>
                                    <th align="left" style="font-size:13px;padding:3px 9px;text-transform: uppercase;width:90px;">Unit Price</th>
                                    <th align="right" style="font-size:13px;padding:3px 9px;text-transform: uppercase;">Total</th>
                                </tr>
                            </thead>
                            <?php
                            if ($_orderItems = getOrderItems($_order)):
                                foreach ($_orderItems as $_item) :
                            ?>
                            <tbody>
                                <tr>
                                    <td height="30" align="left" valign="center" style="padding:3px 9px;"><strong style="font-size:11px;"><?php echo $_item["sku"]?></strong></td>
                                    <td align="left" valign="center" style="font-size:11px;padding:3px 9px;"><?php echo $_item["name"]?></td>
                                    <td align="center" valign="center" style="font-size:11px;padding:3px 9px;"><?php echo (int)$_item["qty"]?></td>
                                    <td align="center" valign="center" style="font-size:11px;padding:3px 9px;"><?php echo $_item["price"]?></td>
                                    <td align="right" valign="center" style="font-size:11px;padding:3px 9px;"><span class="m_-9064054173632497493price"><?php echo $_item["subtotal"]?></span></td>
                                </tr>
                            </tbody>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </table>
                        <table width="100%" border="0" cellspacing="5" cellpadding="0" style="border-collapse: collapse;">
                          <tfoot style="padding:20px;">
                            <tr class="m_-9064054173632497493subtotal">
                              <td colspan="3" align="right" style="padding:3px 9px;text-transform: uppercase;">Subtotal</td>
                              <td width="24%" align="right" style="padding:3px 9px"><?php echo $_orderData["amount"]["subtotal"]?></td>
                            </tr>
                            <tr class="m_-9064054173632497493shipping">
                                <td colspan="3" align="right" style="padding:3px 9px;text-transform: uppercase;">Discount</td>
                                <td width="24%" align="right" style="padding:3px 9px"><?php echo $_orderData["amount"]["discount"]?></td>
                            </tr>
                            <tr class="m_-9064054173632497493shipping">
                              <td colspan="3" align="right" style="padding:3px 9px">Shipping &amp; Handling</td>
                              <td align="right" style="padding:3px 9px"><?php echo $_orderData["amount"]["shipping"]?></td>
                            </tr>
                            <tr class="m_-9064054173632497493grand_total" style="border-top:2px solid #000;">
                              <td colspan="3" align="right" style="padding:3px 9px;text-transform: uppercase;"><strong>Grand Total</strong></td>
                              <td align="right" style="padding:3px 9px;"><strong><?php echo $_orderData["amount"]["grand_total"]?></strong></td>
                            </tr>
                          </tfoot>
                        </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="outer" width="650" style="" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff">
                    <!--footer-->
                    <tr>
                        <td width="450" style="vertical-align: top;">
                            <h5 style="text-transform: uppercase;padding: 25px 0 0 10px;font-size: 12px;">SHIPPING INFO:</h5>
                            <div style="font-size: 10px;padding:0 12px; line-height: 14px;border-right:1px solid #000;">
                                <p style="margin:20px 0 0 0;">Shipping Carrier : <?php echo $_orderData["carrier"]['carrier']?><?php //echo $_orderData["amount"]["grand_total"]?></p>
                                <p style="margin:4px 0;">Shipping Cost    : <?php echo $_orderData["amount"]["shipping"]?></p>
                                <p style="margin:4px 0;">Due Date         : <?php echo $_orderData["due_date"]?></p>
                            </div>
                        </td>
                        <td width="450" style="vertical-align: top;">
                            <h5 style="text-transform: uppercase;padding: 25px 0 0 10px;font-size: 12px;">Payment method:</h5>
                            <div style="font-size: 10px;padding:0 12px;">
                                <?php echo $_orderData["payment_info"];?>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="<?php echo __WEB_BASE__?>/js/html2canvas/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo __WEB_BASE__?>/js/html2canvas/jsPdf.debug.js"></script>
<script type="text/javascript">

    var downPdf = document.getElementById("renderPdf");

    downPdf.onclick = function() {
        html2canvas(document.getElementById("print"), {
            onrendered:function(canvas) {

                var contentWidth = canvas.width;
                var contentHeight = canvas.height;

                //一页pdf显示html页面生成的canvas高度;
                var pageHeight = contentWidth / 592.28 * 841.89;
                //未生成pdf的html页面高度
                var leftHeight = contentHeight;
                //pdf页面偏移
                var position = 0;
                //a4纸的尺寸[595.28,841.89]，html页面生成的canvas在pdf中图片的宽高
                var imgWidth = 595.28;
                var imgHeight = 592.28/contentWidth * contentHeight;

                var pageData = canvas.toDataURL('image/jpeg', 1.0);

                var pdf = new jsPDF('', 'pt', 'a4');

                //有两个高度需要区分，一个是html页面的实际高度，和生成pdf的页面高度(841.89)
                //当内容未超过pdf一页显示的范围，无需分页
                if (leftHeight < pageHeight) {
                    pdf.addImage(pageData, 'JPEG', 0, 0, imgWidth, imgHeight );
                } else {
                    while(leftHeight > 0) {
                        pdf.addImage(pageData, 'JPEG', 0, position, imgWidth, imgHeight);
                        leftHeight -= pageHeight;
                        position -= 841.89;
                        //避免添加空白页
                        if(leftHeight > 0) {
                            pdf.addPage();
                        }
                    }
                }
                pdf.save('invoice.pdf');
            }
        })
    }
</script>
