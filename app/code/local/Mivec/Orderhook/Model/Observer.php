<?php
class Mivec_Orderhook_Model_Observer
{
    public function implementOrderStatus($event)
    {
        $order = $event->getOrder();

        //set banktransfer can execute only.
        if ($this->_getPaymentMethod($order) == 'banktransfer') {
            if ($order->canInvoice())
                $this->_processOrderStatus($order);
            //$this->_sendmail($order);
            //$this->_requestInvoiceEmail($order);
        }
        return $this;
    }

    private function _requestInvoiceEmail($order)
    {
        $_helper = Mage::helper("orderhook");

        $_invoiceUrl = Mage::getBaseUrl() . "mivec/order/invoice/get.php";
        $_orderId = $order->getIncrementId();
        $_token = $_helper->encryptToken(array($_orderId));//print_r($_token);print_r($_helper->decryptToken($_token));exit;

        $httpClientConfig = array(
            'maxredirects' => 0,
            'curloptions' => array(CURLOPT_HEADER => false),
        );
        $client = new Zend_Http_Client($_invoiceUrl, $httpClientConfig);
        $client->setMethod(Zend_Http_Client::POST);
        $client->setParameterPost(array(
            "order_id"  => $_orderId,
            "token"     => $_token,
            "mail"      => 1
        ));

        //mail setting
        $store = Mage::app()->getStore();
        $_subject = $store->getFrontendName() . ": New Order #" . $_orderId;

        try {
            $response = $client->request();
            //test sendmail
            $sendMail = Mage::helper("orderhook/mail")->sendMail(
                $order->getCustomerEmail(),
                $_subject,
                $response->getBody()
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    private function _getPaymentMethod($order)
    {
        return $order->getPayment()->getMethodInstance()->getCode();
    }

    private function _processOrderStatus($order)
    {
        $invoice = $order->prepareInvoice();

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::NOT_CAPTURE);
        $invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_OPEN);

        $invoice->register();
        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();

        //$invoice->sendEmail(true, '');
        $this->_changeOrderStatus($order);
        return true;
    }

    private function _changeOrderStatus($order)
    {
        $statusMessage = '';
        $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true);
        $order->save();
    }

    //remove
    protected function _sendmail($order)
    {
        return $order->sendNewOrderEmail();
    }
}