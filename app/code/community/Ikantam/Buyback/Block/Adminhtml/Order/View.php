<?php

class Ikantam_Buyback_Block_Adminhtml_Order_View extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_blockGroup  = 'buyback';
        $this->_objectId    = 'order_id';
        $this->_controller  = 'adminhtml_order';
        $this->_mode        = 'view';

        parent::__construct();

        $this->_removeButton('reset');
        $this->_removeButton('save');

        $this->setId('sales_order_view');
        $order = $this->getOrder();

        $this->_addButton('edit', array(
            'label'     => Mage::helper('buyback')->__('Edit'),
            'onclick'   => 'setLocation(\'' . $this->getEditUrl() . '\')',
        ));

        if ($this->_isAllowedAction('emails') && !$order->isCanceled()) {
            $message = Mage::helper('sales')->__('Are you sure you want to send order email to customer?');
            $this->addButton('send_notification', array(
                'label'     => Mage::helper('sales')->__('Send Email'),
                'onclick'   => "confirmSetLocation('{$message}', '{$this->getEmailUrl()}')",
            ));
        }

//
//        if ($this->_isAllowedAction('unhold') && $order->canUnhold()) {
//            $this->_addButton('order_unhold', array(
//                'label'     => Mage::helper('sales')->__('Unhold'),
//                'onclick'   => 'setLocation(\'' . $this->getUnholdUrl() . '\')',
//            ));
//        }


//
//        if ($this->_isAllowedAction('ship') && $order->canShip()
//            && !$order->getForcedDoShipmentWithInvoice()) {
//            $this->_addButton('order_ship', array(
//                'label'     => Mage::helper('sales')->__('Ship'),
//                'onclick'   => 'setLocation(\'' . $this->getShipUrl() . '\')',
//                'class'     => 'go'
//            ));
//        }

//        if ($this->_isAllowedAction('reorder')
//            && $this->helper('sales/reorder')->isAllowed($order->getStore())
//            && $order->canReorderIgnoreSalable()
//        ) {
//            $this->_addButton('order_reorder', array(
//                'label'     => Mage::helper('sales')->__('Reorder'),
//                'onclick'   => 'setLocation(\'' . $this->getReorderUrl() . '\')',
//                'class'     => 'go'
//            ));
//        }
    }

    /**
     * Retrieve order model object
     *
     * @return Ikantam_Buyback_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('buyback_order');
    }

    /**
     * Retrieve Order Identifier
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->getOrder()->getId();
    }

    public function getHeaderText()
    {
        if ($_extOrderId = $this->getOrder()->getExtOrderId()) {
            $_extOrderId = '[' . $_extOrderId . '] ';
        } else {
            $_extOrderId = '';
        }
        return Mage::helper('sales')->__('Order # %s %s | %s', $this->getOrder()->getRealOrderId(), $_extOrderId, $this->formatDate($this->getOrder()->getCreatedAtDate(), 'medium', true));
    }

    public function getUrl($params='', $params2=array())
    {
        $params2['order_id'] = $this->getOrderId();
        return parent::getUrl($params, $params2);
    }

    public function getEditUrl()
    {
        return $this->getUrl('*/buyback_order_edit/start');
    }

    public function getEmailUrl()
    {
        return $this->getUrl('*/*/email');
    }

    public function getCancelUrl()
    {
        return $this->getUrl('*/*/cancel');
    }


//    public function getHoldUrl()
//    {
//        return $this->getUrl('*/*/hold');
//    }

//    public function getUnholdUrl()
//    {
//        return $this->getUrl('*/*/unhold');
//    }
//
//    public function getShipUrl()
//    {
//        return $this->getUrl('*/sales_order_shipment/start');
//    }

//    public function getCommentUrl()
//    {
//        return $this->getUrl('*/*/comment');
//    }

//    public function getReorderUrl()
//    {
//        return $this->getUrl('*/sales_order_create/reorder');
//    }


    protected function _isAllowedAction($action)
    {
        return  true;// Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/' . $action);
    }

    /**
     * Return back url for view grid
     *
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->getOrder()->getBackUrl()) {
            return $this->getOrder()->getBackUrl();
        }

        return $this->getUrl('*/*/');
    }

//    public function getReviewPaymentUrl($action)
//    {
//        return $this->getUrl('*/*/reviewPayment', array('action' => $action));
//    }
////
//    /**
//     * Return URL for accept payment action
//     *
//     * @return string
//     */
//    public function getAcceptPaymentUrl()
//    {
//        return $this->getUrl('*/*/reviewPayment', array('action' => 'accept'));
//    }
//
//    /**
//     * Return URL for deny payment action
//     *
//     * @return string
//     */
//    public function getDenyPaymentUrl()
//    {
//        return $this->getUrl('*/*/reviewPayment', array('action' => 'deny'));
//    }
//
//    public function getPaymentReviewUpdateUrl()
//    {
//        return $this->getUrl('*/*/reviewPaymentUpdate');
//    }
}
