<?php

class Ikantam_Buyback_Model_Resource_Quote_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('buyback/quote');
    }

}

