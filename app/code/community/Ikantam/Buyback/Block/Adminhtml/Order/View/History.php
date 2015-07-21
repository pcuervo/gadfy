<?php

class Ikantam_Buyback_Block_Adminhtml_Order_View_History extends Ikantam_Buyback_Block_Adminhtml_Order_View_Abstract
{

    public function canSendCommentEmail()
    {
        return  true;//Mage::helper('sales')->canSendOrderCommentEmail($this->getOrder()->getStore()->getId());
    }


    public function canAddComment()
    {
        return Mage::getSingleton('admin/session')->isAllowed('buyback/order/actions/comment') &&
               $this->getOrder()->canComment();
    }



}
