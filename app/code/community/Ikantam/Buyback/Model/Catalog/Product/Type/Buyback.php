<?php

class Ikantam_Buyback_Model_Catalog_Product_Type_Buyback extends Mage_Catalog_Model_Product_Type_Abstract
{
    public function isVirtual($product = null)
    {
        return true;
    }
}
