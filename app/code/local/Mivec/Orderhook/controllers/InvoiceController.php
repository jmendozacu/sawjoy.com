<?php
class Mivec_Orderhook_InvoiceController extends Mage_Core_Controller_Front_Action
{
    protected function _init()
    {
        $this->getLayout();
        return $this;
    }

    public function getAction()
    {
        $_helper = Mage::helper("orderhook");

        $_invoiceUrl = Mage::getBaseUrl() . "mivec/order/invoice/get.php";
        $this->_init();
        $_orderId = $this->getRequest()->getParam("order_id");
        $_token = $_helper->encryptToken(array($_orderId));//print_r($_token);print_r($_helper->decryptToken($_token));exit;
        $_pdf = $this->getRequest()->getParam("pdf");
        $_mail = $this->getRequest()->getParam("mail"); //

        if ($_orderId) {
            //get customer
            $_order = Mage::getModel("sales/order")->load($_orderId , "increment_id");

            $httpClientConfig = array(
                'maxredirects' => 0,
                'curloptions' => array(CURLOPT_HEADER => false),
            );
            $client = new Zend_Http_Client($_invoiceUrl, $httpClientConfig);
            $client->setMethod(Zend_Http_Client::POST);
            $client->setParameterPost(array(
                "order_id"  => $_orderId,
                "token"     => $_token,
                "pdf"       => $_pdf
            ));

            //mail setting
            $store = Mage::app()->getStore();
            $_subject = $store->getFrontendName() . ": New Order #" . $_orderId;

            try {
                $response = $client->request();
                //print_r($response);exit;
                if ($_mail) {
                    //test sendmail
                    $sendMail = Mage::helper("orderhook/mail")->sendMail(
                        $_order->getCustomerEmail(),
                        $_subject,
                        $response->getBody()
                    );
                } else {
                    print_r($response->getBody());exit;
                }
            } catch (Exception $e) {
                Mage::throwException($this->__('Gateway request error: %s', $e->getMessage()));
            }
        }
    }
}
?>