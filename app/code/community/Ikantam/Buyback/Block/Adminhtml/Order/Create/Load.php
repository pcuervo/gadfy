<?php

class Ikantam_Buyback_Block_Adminhtml_Order_Create_Load extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $result = array();
        foreach ($this->getSortedChildren() as $name) {
            if (!$block = $this->getChild($name)) {
                $result[$name] = Mage::helper('sales')->__('Invalid block: %s.', $name);
            } else {
                $result[$name] = $block->toHtml();
            }
        }
        $resultJson = Mage::helper('core')->jsonEncode($result);
        $jsVarname = $this->getRequest()->getParam('as_js_varname');
        if ($jsVarname) {
            return Mage::helper('adminhtml/js')->getScript(sprintf('var %s = %s', $jsVarname, $resultJson));
        } else {
            return $resultJson;
        }
    }
}
