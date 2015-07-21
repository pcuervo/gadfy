<?php

class Ikantam_Buyback_Model_Resource_Order_Item_Collection extends Mage_Sales_Model_Resource_Order_Item_Collection
{
    protected $_eventPrefix = 'buyback_order_item_collection';
    protected $_eventObject = 'order_item_collection';

    protected function _construct()
    {
        $this->_init('buyback/order_item');
    }
}

