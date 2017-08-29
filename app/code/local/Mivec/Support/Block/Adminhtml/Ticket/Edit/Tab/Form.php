<?php
class Mivec_Support_Block_Adminhtml_Ticket_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $ticketData = Mage::registry("ticket_data");
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('ticket_form' , array('legend'    => 'View Ticket '));

        $_customer = $this->helper('support/customer')
            ->getCustomer($ticketData->getCustomerId());


        $fieldset->addField('customer' , 'link' , array(
            'label'     => 'Customer email',
            'name'      => 'email',
            'value'     => $_customer->getEmail(),
        ));

        $fieldset->addField('order_id' , 'link' , array(
            'label'     => 'Order Number',
            'value'     => $ticketData->getOrderNumber(),
            //'url'       => $this->getUrl('adminhtml/sales_order/view/' , array('order_id' => $order->getId()))
        ));

        $fieldset->addField('subject' , 'link'  ,array(
            'label'     => 'Subject',
            'value'     => $ticketData->getSubject()
        ));

        $_type = Mage::helper("support/type");
        $fieldset->addField('type' , 'select' , array(
            'label'     => 'Issue Type',
            'name'      => 'ticket[type]',
            'value'     => $ticketData->getType(),
            'options'   => $_type->toOptions(),
        ));

        $_deparment = Mage::helper("support/department");
        $fieldset->addField('department' , 'select' , array(
            'label'     => 'Department',
            'name'      => 'ticket[department]',
            'value'     => $ticketData->getAgent(),
            'options'   => $_deparment->toOptions()
        ));

        $fieldset->addField('priority' , 'select' , array(
            'label'     => 'Priority',
            'name'      => 'ticket[priority]',
            'value'     => $ticketData->getPriority(),
            'options'   => Mivec_Support_Model_Ticket_Priority::getPriority(),
        ));

        $fieldset->addField('status' , 'select' , array(
            'label'     => 'Status',
            'name'      => 'ticket[status]',
            'value'     => $ticketData->getStatus(),
            'options'   => Mivec_Support_Model_Ticket_Status::getStatus()
        ));

        $fieldset->addField('created_at' , 'link' , array(
            'label'     => 'Created Date',
            //'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            //'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'value'     => $ticketData->getCreatedAt(),
            //'disabled'  => true,
        ));

        $fieldset->addField("updated_at" , "link" ,
            array(
                "label"     => "Updated Date",
                "value"     => $ticketData->getUpdatedAt()
            )
        );

        $fieldset->addField('reply' , 'textarea' , array(
            'label'     => 'Reply Ticket',
            'name'      => 'ticket[content]',
            'required'  => 'yes'
        ));

        return parent::_prepareForm();
    }
}