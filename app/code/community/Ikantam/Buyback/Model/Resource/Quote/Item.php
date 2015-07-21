<?php

class Ikantam_Buyback_Model_Resource_Quote_Item extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('buyback/quote_item', 'item_id');
    }


}