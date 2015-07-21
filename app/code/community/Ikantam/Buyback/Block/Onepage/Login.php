<?php

class Ikantam_Buyback_Block_Onepage_Login extends Mage_Checkout_Block_Onepage_Login
{

    public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('buyback/session');
        }
        return $this->_checkout;
    }

}
