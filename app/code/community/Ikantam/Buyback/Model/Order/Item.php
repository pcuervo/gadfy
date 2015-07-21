<?php

class Ikantam_Buyback_Model_Order_Item extends Mage_Sales_Model_Order_Item
{
    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('buyback/order_item');
    }
    public function getOrder()
    {
        if (is_null($this->_order) && ($orderId = $this->getOrderId())) {
            $order = Mage::getModel('buyback/order');
            $order->load($orderId);
            $this->setOrder($order);
        }
        return $this->_order;
    }


}
