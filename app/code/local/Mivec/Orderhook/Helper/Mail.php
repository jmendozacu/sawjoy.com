<?php
class Mivec_Orderhook_Helper_Mail extends Mage_Core_Helper_Abstract
{
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';

    public function sendMail($_recipient , $_subject , $_content)
    {
        $_host = trim(Mage::getStoreConfig('smtppro/general/smtp_host'));
        $_config = $this->_config();
        $mail = new Zend_Mail();

        $transport = new Zend_Mail_Transport_Smtp($_host, $_config);

        $send = $mail->setBodyHtml($_content)
            ->setFrom(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT), 'Service')
            ->addTo($_recipient)
            ->setSubject($_subject);
        //print_r($send);exit;

        try {
            if ($send->send($transport)) {
                return true;
            }
        } catch (Exception $e) {
            echo $e->getCode() . " " . $e->getMessage();
        }
    }

    protected function _config()
    {
        $_smtpConfig = array(
            "port"  => Mage::getStoreConfig('smtppro/general/smtp_port'),
            "username"  => Mage::getStoreConfig('smtppro/general/smtp_username'),
            "password"  => Mage::getStoreConfig('smtppro/general/smtp_password'),
            "ssl"   => Mage::getStoreConfig('smtppro/general/smtp_ssl'),
            //"ssl"       => "tls",
            "auth"  => Mage::getStoreConfig('smtppro/general/smtp_authentication')
        );
        return $_smtpConfig;
    }

    public function _sendMail($data , $method = 'new')
    {
        //初始化mail
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        if (is_array($data)) {
            try {
                $template = array(
                    'header'=>"We have got your new ticket. Your ticket is waiting for processing. We will send you email for the updates. Or you can check below URL for ticket updates.",
                    'content'=>''
                );
                $_templateId = 5;
                $data['header'] = $template['header'];
                $postObject = new Varien_Object();
                $postObject->setData($data);
                //setup mail notification //edit by mivec
                $mailTemplate = Mage::getModel('core/email_template');

                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    //->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER))
                    ->sendTransactional(
                        $_templateId,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $data['email'],
                        null,
                        array('data' => $postObject)
                    );
                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }
                $translate->setTranslateInline(true);
                return true;
            } catch (Exception $e) {
                Mage::logException($e);
                $translate->setTranslateInline(true);
                return false;
            }
        }
    }
}