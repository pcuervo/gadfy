<?php

class Ikantam_Buyback_Block_Adminhtml_Customer_Edit_Tab_Buyback
 extends Mage_Adminhtml_Block_Template
 implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected $_customer;

    protected $_customerLog;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ikantam/buyback/customer/tab/buyback.phtml');
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = Mage::registry('current_customer');
        }
        return $this->_customer;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $grid = $this->getLayout()->createBlock('buyback/adminhtml_customer_edit_tab_buyback_grid', 'buyback_order_grid');
        $this->setChild('buyback_order_grid', $grid);
    }

    public function getTabLabel()
    {
        return Mage::helper('buyback')->__('Buyback Orders');
    }

    public function getTabTitle()
    {
        return Mage::helper('buyback')->__('Buyback Orders');

    }

    public function canShowTab()
    {
        if (Mage::registry('current_customer')->getId()) {
            return true;
        }
        return false;
    }

    public function isHidden()
    {
        if (Mage::registry('current_customer')->getId()) {
            return false;
        }
        return true;
    }

    public function getAfter()
    {
        return 'addresses';
    }
}
