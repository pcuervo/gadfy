<?php
class Ikantam_Buyback_Model_Resource_Quote_Item_Option extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Main table and field initialization
     *
     */
    protected function _construct()
    {
        $this->_init('buyback/quote_item_option', 'option_id');
    }
}
