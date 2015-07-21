<?php

class Ikantam_Buyback_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{

    protected function _prepareLastOrder()
    {
        $orderId = Mage::getSingleton('buyback/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('buyback/order')->load($orderId);
            if ($order->getId()) {
                $isVisible = !in_array($order->getState(),
                    Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
                $this->addData(array(
                    'is_order_visible' => $isVisible,
                    'view_order_id' => $this->getUrl('buyback/order/view/', array('order_id' => $orderId)),
                    'can_view_order'  => Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible,
                    'order_id'  => $order->getIncrementId(),
                ));
            }
        }
    }

    /**
     * Prepare billing agreement data from an identifier in the session
     */
    protected function _prepareLastBillingAgreement()
    {
        return $this;
    }

    /**
     * Prepare recurring payment profiles from the session
     */
    protected function _prepareLastRecurringProfiles()
    {
        $profileIds = Mage::getSingleton('buyback/session')->getLastRecurringProfileIds();
        if ($profileIds && is_array($profileIds)) {
            $collection = Mage::getModel('sales/recurring_profile')->getCollection()
                ->addFieldToFilter('profile_id', array('in' => $profileIds))
            ;
            $profiles = array();
            foreach ($collection as $profile) {
                $profiles[] = $profile;
            }
            if ($profiles) {
                $this->setRecurringProfiles($profiles);
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $this->setCanViewProfiles(true);
                }
            }
        }
    }
}
