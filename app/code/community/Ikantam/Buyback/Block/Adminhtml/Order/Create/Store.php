<?php
class Ikantam_Buyback_Block_Adminhtml_Order_Create_Store extends Ikantam_Buyback_Block_Adminhtml_Order_Create_Abstract
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_create_store');
    }

    public function getHeaderText()
    {
        return Mage::helper('sales')->__('Please Select a Store');
    }
}
