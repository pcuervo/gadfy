<?php

class  Ikantam_Buyback_Block_Adminhtml_Order_Create extends Mage_Adminhtml_Block_Sales_Order_Create
{

    /**
     * Check access for cancel action
     *
     * @return boolean
     */
    protected function _isCanCancel()
    {
        return true;
    }

    /**
     * Prepare header html
     *
     * @return string
     */
    public function getHeaderHtml()
    {
        $out = '<div id="order-header">'
            . $this->getLayout()->createBlock('buyback/adminhtml_order_create_header')->toHtml()
            . '</div>';
        return $out;
    }



    /**
     * Retrieve quote session object
     *
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    protected function _getSession()
    {
        return Mage::getSingleton('buyback/adminhtml_session_quote');
    }

    public function getCancelUrl()
    {
        return $this->getUrl('*/*/cancel');
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/' . $this->_controller . '/');
    }
}
