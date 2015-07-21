<?php
require_once('CreateController.php');

class Ikantam_Buyback_Adminhtml_Buyback_Order_EditController extends Ikantam_Buyback_Adminhtml_Buyback_Order_CreateController
{
    /**
     * Start edit order initialization
     */
    public function startAction()
    {
        $this->_getSession()->clear();
        $orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('buyback/order')->load($orderId);

        try {
            if ($order->getId()) {
                $this->_getOrderCreateModel()->initFromOrder($order);
                $this->_initSession();
                $this->_redirect('*/*');
            }
            else {
                $this->_redirect('*/buyback_order/');
            }
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/buyback_order/view', array('order_id' => $orderId));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addException($e, $e->getMessage());
            $this->_redirect('*/buyback_order/view', array('order_id' => $orderId));
        }
    }

    /**
     * Index page
     */
    public function indexAction()
    {
        $this->_title($this->__('Buyback'))->_title($this->__('Orders'))->_title($this->__('Edit Buyback Order'));
        $this->loadLayout();

        $this->_initSession()
            ->_setActiveMenu('buyback/order')
            ->renderLayout();
    }

    /**
     * Acl check for admin
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
