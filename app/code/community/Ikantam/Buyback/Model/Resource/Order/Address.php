<?php

class  Ikantam_Buyback_Model_Resource_Order_Address extends Mage_Sales_Model_Resource_Order_Address
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix    = 'buyback_order_address_resource';

    /**
     * Resource initialization
     *
     */
    protected function _construct()
    {
        $this->_init('buyback/order_address', 'entity_id');
    }

    /**
     * Update related grid table after object save
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        return $this;
    }
}
