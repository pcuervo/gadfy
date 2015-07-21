<?php

class Ikantam_Buyback_Model_Resource_Order_Collection extends Mage_Sales_Model_Resource_Order_Collection
{
    protected function _construct()
    {
        $this->_init('buyback/order');
    }

}

