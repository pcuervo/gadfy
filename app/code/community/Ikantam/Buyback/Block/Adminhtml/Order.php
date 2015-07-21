<?php
class Ikantam_Buyback_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'buyback';
        $this->_controller = 'adminhtml_order';
        $this->_headerText = Mage::helper('buyback')->__('Buyback Orders');
        $this->_addButtonLabel = Mage::helper('buyback')->__('Create New Buyback Order');
        parent::__construct();

    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/buyback_order_create');
    }
}
