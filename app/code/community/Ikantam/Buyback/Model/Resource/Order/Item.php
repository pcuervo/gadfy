<?php

class Ikantam_Buyback_Model_Resource_Order_Item extends Mage_Sales_Model_Resource_Order_Item
{
    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('buyback/order_item', 'item_id');
    }


}
