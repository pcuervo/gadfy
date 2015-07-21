<?php
/**
 * Class Ikantam_Buyback_Model_Order
 * @method getPaymentInfo()
 */
class Ikantam_Buyback_Model_Order extends Mage_Sales_Model_Order
{
    protected $_eventPrefix = 'buyback_order';

    const XML_PATH_EMAIL_TEMPLATE               = 'buyback/order/template';
    const XML_PATH_EMAIL_GUEST_TEMPLATE         = 'buyback/order/guest_template';
    const XML_PATH_EMAIL_IDENTITY               = 'buyback/order/identity';
    const XML_PATH_EMAIL_COPY_TO                = 'buyback/order/copy_to';
    const XML_PATH_EMAIL_COPY_METHOD            = 'buyback/order/copy_method';
    const XML_PATH_EMAIL_ENABLED                = 'buyback/order/enabled';

    const XML_PATH_UPDATE_EMAIL_TEMPLATE        = 'buyback/order_comment/template';
    const XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE  = 'buyback/order_comment/guest_template';
    const XML_PATH_UPDATE_EMAIL_IDENTITY        = 'buyback/order_comment/identity';
    const XML_PATH_UPDATE_EMAIL_COPY_TO         = 'buyback/order_comment/copy_to';
    const XML_PATH_UPDATE_EMAIL_COPY_METHOD     = 'buyback/order_comment/copy_method';
    const XML_PATH_UPDATE_EMAIL_ENABLED         = 'buyback/order_comment/enabled';



    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('buyback/order');
    }
    public function getItemsCollection($filterByTypes = array(), $nonChildrenOnly = false)
    {
        if (is_null($this->_items)) {
            $this->_items = Mage::getResourceModel('buyback/order_item_collection')
                ->setOrderFilter($this);

            if ($filterByTypes) {
                $this->_items->filterByTypes($filterByTypes);
            }
            if ($nonChildrenOnly) {
                $this->_items->filterByParent();
            }

            if ($this->getId()) {
                foreach ($this->_items as $item) {
                    $item->setOrder($this);
                }
            }
        }
        return $this->_items;
    }

    public function place()
    {
        Mage::dispatchEvent('buyback_order_place_before', array('order'=>$this));
//        $this->_placePayment();
        $this->setState('buyback', 'waiting_for_reception'); // set status
        $this->setGrandTotal($this->getSubtotal());
        Mage::dispatchEvent('buyback_order_place_after', array('order'=>$this));
        return $this;
    }


    protected function _placePayment()
    {
//        $this->getPayment()->place();
        return $this;
    }

    public function sendNewOrderEmail()
    {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);


        $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getCustomerEmail(), $this->getCustomerName());
        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        $this->setPaymentInfo(nl2br($this->getPaymentInfo()));
        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                'order'         => $this,
                'shippingInfo' => nl2br(Mage::helper('buyback')->getShippingInfo())
            )
        );
        $mailer->send();

        $this->setEmailSent(true);
        $this->_getResource()->saveAttribute($this, 'email_sent');

        return $this;
    }

    /**
     * Send email with order update information
     *
     * @param boolean $notifyCustomer
     * @param string $comment
     * @return Mage_Sales_Model_Order
     */
    public function sendOrderUpdateEmail($notifyCustomer = true, $comment = '')
    {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendOrderCommentEmail($storeId)) {
            return $this;
        }
        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_UPDATE_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_COPY_METHOD, $storeId);
        // Check if at least one recepient is found
        if (!$notifyCustomer && !$copyTo) {
            return $this;
        }

        // Retrieve corresponding email template id and customer name
        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }

        $mailer = Mage::getModel('core/email_template_mailer');
        if ($notifyCustomer) {
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($this->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
                // Add bcc to customer email
                foreach ($copyTo as $email) {
                    $emailInfo->addBcc($email);
                }
            }
            $mailer->addEmailInfo($emailInfo);
        }

        // Email copies are sent as separated emails if their copy method is
        // 'copy' or a customer should not be notified
        if ($copyTo && ($copyMethod == 'copy' || !$notifyCustomer)) {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                'order'   => $this,
                'comment' => $comment,
                'billing' => $this->getBillingAddress()
            )
        );
        $mailer->send();

        return $this;
    }


    protected function _afterSave()
    {
        $this->_payments = null;
//        $this->_statusHistory = null;
        return parent::_afterSave();
    }

    public function canEdit()
    {
        if ($this->canUnhold()) {
            return false;
        }

        $state = $this->getState();
        if ($this->isCanceled() || $this->isPaymentReview()
            || $state === self::STATE_COMPLETE || $state === self::STATE_CLOSED) {
            return false;
        }


        if ($this->getActionFlag(self::ACTION_FLAG_EDIT) === false) {
            return false;
        }

        return true;
    }


    public function canVoidPayment()
    {
        return false;
    }

    public function getPaymentsCollection()
    {
        if (is_null($this->_payments)) {
            $this->_payments = Mage::getResourceModel('sales/order_payment_collection')->setPageSize(1,1);
        }
        return $this->_payments;
    }

    public function addStatusHistoryComment($comment, $status = false)
    {


        if (false === $status) {
            $status = $this->getStatus();
        } elseif (true === $status) {
            $status = $this->getConfig()->getStateDefaultStatus($this->getState());
        } else {
            $this->setStatus($status);
        }
        $history = Mage::getModel('buyback/order_status_history')
            ->setStatus($status)
            ->setComment($comment)
            ->setEntityName($this->_historyEntityName);
        $this->addStatusHistory($history);
        return $history;
    }

    public function getStatusHistoryCollection($reload=false)
    {
        if (is_null($this->_statusHistory) || $reload) {
            $this->_statusHistory = Mage::getResourceModel('buyback/order_status_history_collection')
                ->setOrderFilter($this)
                ->setOrder('created_at', 'desc')
                ->setOrder('entity_id', 'desc');

            if ($this->getId()) {
                foreach ($this->_statusHistory as $status) {
                    $status->setOrder($this);
                }
            }
        }
        return $this->_statusHistory;
    }

    public function addStatusHistory(Mage_Sales_Model_Order_Status_History $history)
    {
        $history->setOrder($this);
        $this->setStatus($history->getStatus());
        if (!$history->getId()) {
            $this->getStatusHistoryCollection()->addItem($history);
        }
        return $this;
    }

    protected function _checkState()
    {
        return $this;
    }


    protected function _beforeSave()
    {
        if ($this->getOriginalIncrementId()) { // if edit
            $this->getIncrementId($this->getOriginalIncrementId());
        } else if (strpos($this->getIncrementId(), 'buyback-') === false) {
            $incrementId = Mage::getSingleton('eav/config')
                ->getEntityType('order')
                ->fetchNewIncrementId($this->getStoreId());

            $this->setIncrementId('buyback-'.(int)$incrementId);
        }
        parent::_beforeSave();
    }

    protected function _beforeDelete()
    {
        foreach ($this->getItemsCollection() as $_item) {
            $_item->delete();
        }
        return parent::_beforeDelete();

    }

    public function canComment()
    {
//        if ($this->getActionFlag(self::ACTION_FLAG_COMMENT) === false) {
//            return false;
//        }
        return true;
    }

    public function getAddressesCollection()
    {
        if (is_null($this->_addresses)) {
            $this->_addresses = Mage::getResourceModel('buyback/order_address_collection')
                ->setOrderFilter($this);

            if ($this->getId()) {
                foreach ($this->_addresses as $address) {
                    $address->setOrder($this);
                }
            }
        }

        return $this->_addresses;
    }

}
