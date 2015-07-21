<?php
/**
 * Class Ikantam_Buyback_Helper_Data
 */
class Ikantam_Buyback_Helper_Data  extends Mage_Core_Helper_Abstract
{
    /**
     * Maximum available number
     */
    const MAXIMUM_AVAILABLE_NUMBER = 99999999;


    const  XML_PATH_ENABLED            = 'buyback/buyback/enabled';
    const  XML_PATH_SHIPPING_INFO      = 'buyback/buyback/shipping_info';
    const  XML_PATH_PAYMENT_INFO_TITLE = 'buyback/buyback/payment_info_title';

    public function isEnabled()
    {
        return  Mage::getStoreConfig(self::XML_PATH_ENABLED);
    }

    public function getShippingInfo()
    {
        return Mage::getStoreConfig(self::XML_PATH_SHIPPING_INFO);
    }

    public function getPaymentInfoTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_PAYMENT_INFO_TITLE);
    }


    public function checkQuoteAmount(Ikantam_Buyback_Model_Quote $quote, $amount)
    {
        if (!$quote->getHasError() && ($amount>=self::MAXIMUM_AVAILABLE_NUMBER)) {
            $quote->setHasError(true);
            $quote->addMessage(
                $this->__('Items maximum quantity or price do not allow checkout.')
            );
        }
        return $this;
    }

    /**
     * Check allow to send new order confirmation email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendNewOrderConfirmationEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send new order email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendNewOrderEmail($store = null)
    {
        return $this->canSendNewOrderConfirmationEmail($store);
    }

    /**
     * Check allow to send order comment email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendOrderCommentEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send new shipment email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendNewShipmentEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Shipment::XML_PATH_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send shipment comment email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendShipmentCommentEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Shipment::XML_PATH_UPDATE_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send new invoice email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendNewInvoiceEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Invoice::XML_PATH_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send invoice comment email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendInvoiceCommentEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Invoice::XML_PATH_UPDATE_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send new creditmemo email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendNewCreditmemoEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Creditmemo::XML_PATH_EMAIL_ENABLED, $store);
    }

    /**
     * Check allow to send creditmemo comment email
     *
     * @param mixed $store
     * @return bool
     */
    public function canSendCreditmemoCommentEmail($store = null)
    {
        return Mage::getStoreConfigFlag(Mage_Sales_Model_Order_Creditmemo::XML_PATH_UPDATE_EMAIL_ENABLED, $store);
    }

    /**
     * Get old field map
     *
     * @param string $entityId
     * @return array
     */
    public function getOldFieldMap($entityId)
    {
        $node = Mage::getConfig()->getNode('global/sales/old_fields_map/' . $entityId);
        if ($node === false) {
            return array();
        }
        return (array) $node;
    }


    public function isBuybackProduct($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            return $product->getTypeId() == 'buyback';
        }
        return null;
    }


    private function get_callstack($delim="\n") {
        $dt = debug_backtrace();
        $cs = '';
        foreach ($dt as $t) {
            $cs .= $t['file'] . ' line ' . $t['line'] . ' calls ' . $t['function'] . "()" . $delim;
        }

        return $cs;
    }

    public function toLog() {
        Mage::log($this->get_callstack());
    }

    public function toFirePhp() {
        $stack = $this->get_callstack();
        foreach (explode("\n", $stack) as $line) {
            Mage::helper('firephp')->send($line);
        }
    }
}
