<?php

class Ikantam_Buyback_Model_Resource_Order_Address_Collection  extends Mage_Sales_Model_Resource_Order_Address_Collection
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix    = 'buyback_order_address_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject    = 'order_address_collection';

    /**
     * Model initialization
     *
     */
    protected function _construct()
    {
        $this->_init('buyback/order_address');
    }

}
