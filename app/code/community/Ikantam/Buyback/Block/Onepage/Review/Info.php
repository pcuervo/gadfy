<?php

class Ikantam_Buyback_Block_Onepage_Review_Info extends Mage_Sales_Block_Items_Abstract
{
    public function getItems()
    {
        return Mage::getSingleton('buyback/session')->getQuote()->getAllVisibleItems();
    }

    public function getTotals()
    {
        return Mage::getSingleton('buyback/session')->getQuote()->getTotals();
    }

}
