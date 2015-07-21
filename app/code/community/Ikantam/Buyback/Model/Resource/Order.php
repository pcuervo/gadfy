<?php

class Ikantam_Buyback_Model_Resource_Order extends Mage_Sales_Model_Resource_Order
{
    protected $_eventPrefix                  = 'buyback_order_resource';

    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('buyback/order', 'entity_id');
    }


}
