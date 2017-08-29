<?php
class Mivec_Orderhook_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $_tokenKey = "XXOOXX00";

    public function getCustomer($_customerId)
    {
        return Mage::getModel("customer/customer")->load($_customerId);
    }

    public function encryptToken($data) {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $this->_tokenKey, $prep_code, MCRYPT_MODE_ECB);
        return base64_encode($encrypt);
    }

    public function decryptToken($str) {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $this->_tokenKey, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }

    /*
    public function createInvoice($order)
    {
        $invoice = $order->prepareInvoice();

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::NOT_CAPTURE);
        $invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_OPEN);

        $invoice->register();
        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();

        try {
            $invoice->sendEmail(true, '');
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $this->_changeOrderStatus($order);
        return true;
    }

    private function _changeOrderStatus($order)
    {
        $statusMessage = '';
        $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true);
        $order->save();
    }
    */
}