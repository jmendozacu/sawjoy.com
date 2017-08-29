<?php
class Mivec_Support_Helper_Mail extends Mage_Core_Helper_Abstract
{
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';

    public function sendMail($data , $method = 'new')
    {
        //初始化mail
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        if (is_array($data)) {
            try {
                $template = array(
                    'header'=>'',
                    'content'=>''
                );
                switch($method) {
                    case 'new': {
                        $template['header'] = "We have got your new ticket. Your ticket is waiting for processing. We will send you email for the updates. Or you can check below URL for ticket updates.";
                        //$template['content'] = $data['content'];
                        break;
                    }
                    case 'update': {
                        $template['header'] = "Your Ticket # ".$data['ticket_id']." was updated,You can view this detail in you account dashboard --> My Tickets,or click this url to check.";
                        //$template['content'] = '';
                        break;
                    }
                }
                $data['header'] = $template['header'];
                $postObject = new Varien_Object();
                $postObject->setData($data);

                //setup mail notification //edit by mivec
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */

                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER))
                    ->sendTransactional(
                        7,
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
                $translate->setTranslateInline(true);
                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                return false;
            }
        }
    }
}