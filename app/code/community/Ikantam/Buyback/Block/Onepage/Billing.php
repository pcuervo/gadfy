<?php

class  Ikantam_Buyback_Block_Onepage_Billing extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * Initialize billing address step
     *
     */
    protected function _construct()
    {
        $this->getCheckout()->setStepData('billing', array(
            'label'     => Mage::helper('checkout')->__('Your Payment Information'),
            'is_show'   => $this->isShow()
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('billing', 'allow', true);
        }
        parent::_construct();
    }
    public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('buyback/session');
        }
        return $this->_checkout;
    }
}
