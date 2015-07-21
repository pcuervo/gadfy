<?php

class Ikantam_Buyback_Model_Observer
{
    public function unsetAll()
    {
        Mage::getSingleton('buyback/session')->unsetAll();
    }

    public function loadCustomerQuote()
    {
        try {
            Mage::getSingleton('buyback/session')->loadCustomerQuote();
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('buyback/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('buyback/session')->addException(
                $e,
                Mage::helper('buyback')->__('Load customer buyback quote error')
            );
        }
    }

    public function salesQuoteSaveAfter($observer)
    {
        $quote = $observer->getEvent()->getQuote();
        /* @var $quote Mage_Sales_Model_Quote */
        if ($quote->getIsCheckoutCart()) {
            Mage::getSingleton('buyback/session')->getQuoteId($quote->getId());
        }
    }

}
