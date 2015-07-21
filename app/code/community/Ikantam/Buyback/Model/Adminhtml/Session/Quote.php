<?php
class Ikantam_Buyback_Model_Adminhtml_Session_Quote extends Mage_Adminhtml_Model_Session_Quote
{

    public function __construct()
    {
        $this->init('buyback_adminhtml_quote');
        if (Mage::app()->isSingleStoreMode()) {
            $this->setStoreId(Mage::app()->getStore(true)->getId());
        }
    }

    /**
     * Retrieve quote model object
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (is_null($this->_quote)) {
            $this->_quote = Mage::getModel('buyback/quote');
            if ($this->getStoreId() && $this->getQuoteId()) {
                $this->_quote->setStoreId($this->getStoreId())
                    ->load($this->getQuoteId());
            }
            elseif($this->getStoreId() && $this->hasCustomerId()) {
                $this->_quote->setStoreId($this->getStoreId())
                    ->setCustomerGroupId(Mage::getStoreConfig(self::XML_PATH_DEFAULT_CREATEACCOUNT_GROUP))
                    ->assignCustomer($this->getCustomer())
                    ->setIsActive(false)
                    ->save();
                $this->setQuoteId($this->_quote->getId());
            }
            $this->_quote->setIgnoreOldQty(true);
            $this->_quote->setIsSuperMode(true);
        }
        return $this->_quote;
    }

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (is_null($this->_order)) {
            $this->_order = Mage::getModel('buyback/order');
            if ($this->getOrderId()) {
                $this->_order->load($this->getOrderId());
            }
        }
        return $this->_order;
    }
}
