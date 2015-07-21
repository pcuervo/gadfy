<?php

class Ikantam_Buyback_Block_Adminhtml_Order_Create_Abstract extends Mage_Adminhtml_Block_Sales_Order_Create_Abstract
{
    /**
     * Retrieve create order model object
     *
     * @return Mage_Adminhtml_Model_Sales_Order_Create
     */
    public function getCreateOrderModel()
    {
        return Mage::getSingleton('buyback/adminhtml_order_create');
    }

    /**
     * Retrieve quote session object
     *
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    protected function _getSession()
    {
        return Mage::getSingleton('buyback/adminhtml_session_quote');
    }

}
