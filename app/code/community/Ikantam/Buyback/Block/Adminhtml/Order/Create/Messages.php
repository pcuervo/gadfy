<?php

class Ikantam_Buyback_Block_Adminhtml_Order_Create_Messages  extends Mage_Adminhtml_Block_Messages
{

    public function _prepareLayout()
    {
        $this->addMessages(Mage::getSingleton('buyback/adminhtml_session_quote')->getMessages(true));
        parent::_prepareLayout();
    }

}
