<?php

class Ikantam_Buyback_Block_Onepage_Link extends Mage_Core_Block_Template
{
    public function getCheckoutUrl()
    {
        return $this->getUrl('buyback/onepage', array('_secure'=>true));
    }

    public function isDisabled()
    {
        return !Mage::getSingleton('buyback/session')->getQuote()->validateMinimumAmount();
    }

    public function isPossibleOnepageCheckout()
    {
        return true;
    }
}
