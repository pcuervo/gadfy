<?php

class Ikantam_Buyback_Block_Adminhtml_Order_Create_Search extends Ikantam_Buyback_Block_Adminhtml_Order_Create_Abstract
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_create_search');
    }

    public function getHeaderText()
    {
        return Mage::helper('sales')->__('Please Select Products to Add');
    }

    public function getButtonsHtml()
    {
        $addButtonData = array(
            'label' => Mage::helper('sales')->__('Add Selected Product(s) to Order'),
            'onclick' => 'order.productGridAddSelected()',
            'class' => 'add',
        );
        return $this->getLayout()->createBlock('adminhtml/widget_button')->setData($addButtonData)->toHtml();
    }

    public function getHeaderCssClass()
    {
        return 'head-catalog-product';
    }

}
