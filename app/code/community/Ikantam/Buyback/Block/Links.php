<?php

class Ikantam_Buyback_Block_Links extends Mage_Core_Block_Template
{

    public function addCartLink()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Ikantam_Buyback')) {
            $count = $this->getSummaryQty() ? $this->getSummaryQty()
                : $this->helper('buyback/cart')->getSummaryCount();
            if ($count == 1) {
                $text = $this->__('My Buyback Cart (%s item)', $count);
            } elseif ($count > 0) {
                $text = $this->__('My Buyback Cart (%s items)', $count);
            } else {
                $text = $this->__('My Buyback Cart');
            }

            $parentBlock->removeLinkByUrl($this->getUrl('buyback/cart'));
            $parentBlock->addLink($text, 'buyback/cart', $text, true, array(), 50, null, 'class="top-link-cart"');
        }
        return $this;
    }

    /**
     * Add link on checkout page to parent block
     *
     * @return Mage_Checkout_Block_Links
     */
    public function addCheckoutLink()
    {
        if (!$this->helper('checkout')->canOnepageCheckout()) {
            return $this;
        }

        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
            $text = $this->__('Checkout');
            $parentBlock->addLink(
                $text, 'checkout', $text,
                true, array('_secure' => true), 60, null,
                'class="top-link-checkout"'
            );
        }
        return $this;
    }
}
