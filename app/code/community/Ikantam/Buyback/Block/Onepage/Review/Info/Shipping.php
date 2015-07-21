<?php

class Ikantam_Buyback_Block_Onepage_Review_Info_Shipping extends Mage_Core_Block_Template
{
    public function getShippingInfo()
    {
        return $this->helper('buyback')->getShippingInfo();
    }
}
