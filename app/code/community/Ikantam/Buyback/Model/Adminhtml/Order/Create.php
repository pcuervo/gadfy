<?php

class Ikantam_Buyback_Model_Adminhtml_Order_Create extends Mage_Adminhtml_Model_Sales_Order_Create implements Mage_Checkout_Model_Cart_Interface
{
    public function __construct()
    {
        $this->_session = Mage::getSingleton('buyback/adminhtml_session_quote');
    }

    /**
     * Initialize creation data from existing order
     *
     * @param Mage_Sales_Model_Order $order
     * @return unknown
     */
    public function initFromOrder(Mage_Sales_Model_Order $order)
    {
        if (!$order->getReordered()) {
            $this->getSession()->setOrderId($order->getId());
        } else {
            $this->getSession()->setReordered($order->getId());
        }

        /**
         * Check if we edit quest order
         */
        $this->getSession()->setCurrencyId($order->getOrderCurrencyCode());
        if ($order->getCustomerId()) {
            $this->getSession()->setCustomerId($order->getCustomerId());
        } else {
            $this->getSession()->setCustomerId(false);
        }

        $this->getSession()->setStoreId($order->getStoreId());

        //Notify other modules about the session quote
        Mage::dispatchEvent('init_from_buyback_order_session_quote_initialized',
                array('session_quote' => $this->getSession()));

        /**
         * Initialize catalog rule data with new session values
         */
        $this->initRuleData();

        foreach ($order->getItemsCollection() as $orderItem) {
            /* @var $orderItem Ikantam_Buyback_Model_Order_Item */
            $qty = $orderItem->getQtyOrdered();
            
            if ($qty > 0) {
                $item = $this->initFromOrderItem($orderItem, $qty);
                if (is_string($item)) {
                    Mage::throwException($item);
                }
            }
            
        }
        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress) {
            $addressDiff = array_diff_assoc($shippingAddress->getData(), $order->getBillingAddress()->getData());
            unset($addressDiff['address_type'], $addressDiff['entity_id']);
            $shippingAddress->setSameAsBilling(empty($addressDiff));
        }

        $this->_initBillingAddressFromOrder($order);
        $this->_initShippingAddressFromOrder($order);

        if (!$this->getQuote()->isVirtual() && $this->getShippingAddress()->getSameAsBilling()) {
            $this->setShippingAsBilling(1);
        }

        $this->setShippingMethod($order->getShippingMethod());

        Mage::helper('core')->copyFieldset(
            'sales_copy_order',
            'to_edit',
            $order,
            $this->getQuote()
        );
        $this->getQuote()->setPaymentInfo($order->getPaymentInfo());
        Mage::dispatchEvent('buyback_convert_order_to_quote', array(
            'order' => $order,
            'quote' => $this->getQuote()
        ));

        if (!$order->getCustomerId()) {
            $this->getQuote()->setCustomerIsGuest(true);
        }

        if ($this->getSession()->getUseOldShippingMethod(true)) {
            /*
             * if we are making reorder or editing old order
             * we need to show old shipping as preselected
             * so for this we need to collect shipping rates
             */
            $this->collectShippingRates();
        } else {
            /*
             * if we are creating new order then we don't need to collect
             * shipping rates before customer hit appropriate button
             */
            $this->collectRates();
        }

        // Make collect rates when user click "Get shipping methods and rates" in order creating
        // $this->getQuote()->getShippingAddress()->setCollectShippingRates(true);
        // $this->getQuote()->getShippingAddress()->collectShippingRates();

        $this->getQuote()->save();

        return $this;
    }

    protected function _initBillingAddressFromOrder(Mage_Sales_Model_Order $order)
    {
        $this->getQuote()->getBillingAddress()->setCustomerAddressId('');
        Mage::helper('core')->copyFieldset(
            'sales_copy_order_billing_address',
            'to_order',
            $order->getBillingAddress(),
            $this->getQuote()->getBillingAddress()
        );
    }

    protected function _initShippingAddressFromOrder(Mage_Sales_Model_Order $order)
    {
        $orderShippingAddress = $order->getShippingAddress();
        $quoteShippingAddress = $this->getQuote()->getShippingAddress()
            ->setCustomerAddressId('')
            ->setSameAsBilling($orderShippingAddress && $orderShippingAddress->getSameAsBilling());
        Mage::helper('core')->copyFieldset(
            'sales_copy_order_shipping_address',
            'to_order',
            $orderShippingAddress,
            $quoteShippingAddress
        );
    }

    /**
     * Initialize creation data from existing order Item
     *
     * @param Mage_Sales_Model_Order_Item $orderItem
     * @param int $qty
     * @return Mage_Sales_Model_Quote_Item | string
     */
    public function initFromOrderItem(Mage_Sales_Model_Order_Item $orderItem, $qty = null)
    {
        if (!$orderItem->getId()) {
            return $this;
        }

        $product = Mage::getModel('catalog/product')
            ->setStoreId($this->getSession()->getStoreId())
            ->load($orderItem->getProductId());

        if ($product->getId()) {
            $product->setSkipCheckRequiredOption(true);
            $buyRequest = $orderItem->getBuyRequest();
            if (is_numeric($qty)) {
                $buyRequest->setQty($qty);
            }

            $item = $this->getQuote()->addProduct($product, $buyRequest);

            if (is_string($item)) {
                return $item;
            }

            if ($additionalOptions = $orderItem->getProductOptionByCode('additional_options')) {
                $item->addOption(new Varien_Object(
                    array(
                        'product' => $item->getProduct(),
                        'code' => 'additional_options',
                        'value' => serialize($additionalOptions)
                    )
                ));
            }

            if ($product->getPrice() != $orderItem->getPrice()) {
                $item->setCustomPrice($orderItem->getPrice());
                $item->setOriginalCustomPrice($orderItem->getPrice());
            }

            Mage::dispatchEvent('buyback_convert_order_item_to_quote_item', array(
                'order_item' => $orderItem,
                'quote_item' => $item
            ));
            return $item;
        }

        return $this;
    }

    /**
     * Retrieve customer wishlist model object
     *
     * @params bool $cacheReload pass cached wishlist object and get new one
     * @return Mage_Wishlist_Model_Wishlist
     */
    public function getCustomerWishlist($cacheReload = false)
    {
        if (!is_null($this->_wishlist) && !$cacheReload) {
            return $this->_wishlist;
        }

        if ($this->getSession()->getCustomer()->getId()) {
            $this->_wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer(
                $this->getSession()->getCustomer(), true
            );
            $this->_wishlist->setStore($this->getSession()->getStore())
                ->setSharedStoreIds($this->getSession()->getStore()->getWebsite()->getStoreIds());
        } else {
            $this->_wishlist = false;
        }

        return $this->_wishlist;
    }

    /**
     * Retrieve customer cart quote object model
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getCustomerCart()
    {
        if (!is_null($this->_cart)) {
            return $this->_cart;
        }

        $this->_cart = Mage::getModel('buyback/quote');

        if ($this->getSession()->getCustomer()->getId()) {
            $this->_cart->setStore($this->getSession()->getStore())
                ->loadByCustomer($this->getSession()->getCustomer()->getId());
            if (!$this->_cart->getId()) {
                $this->_cart->assignCustomer($this->getSession()->getCustomer());
                $this->_cart->save();
            }
        }

        return $this->_cart;
    }


    /**
     * Handle data sent from sidebar
     *
     * @param array $data
     * @return Mage_Adminhtml_Model_Sales_Order_Create
     */
    public function applySidebarData($data)
    {
        if (isset($data['add_order_item'])) {
            foreach ($data['add_order_item'] as $orderItemId => $value) {
                /* @var $orderItem Mage_Sales_Model_Order_Item */
                $orderItem = Mage::getModel('buyback/order_item')->load($orderItemId);
                $item = $this->initFromOrderItem($orderItem);
                if (is_string($item)) {
                    Mage::throwException($item);
                }
            }
        }
        if (isset($data['add_cart_item'])) {
            foreach ($data['add_cart_item'] as $itemId => $qty) {
                $item = $this->getCustomerCart()->getItemById($itemId);
                if ($item) {
                    $this->moveQuoteItem($item, 'order', $qty);
                    $this->removeItem($itemId, 'cart');
                }
            }
        }
        if (isset($data['add_wishlist_item'])) {
            foreach ($data['add_wishlist_item'] as $itemId => $qty) {
                $item = Mage::getModel('wishlist/item')
                    ->loadWithOptions($itemId, 'info_buyRequest');
                if ($item->getId()) {
                    $this->addProduct($item->getProduct(), $item->getBuyRequest()->toArray());
                }
            }
        }
        if (isset($data['add'])) {
            foreach ($data['add'] as $productId => $qty) {
                $this->addProduct($productId, array('qty' => $qty));
            }
        }
        if (isset($data['remove'])) {
            foreach ($data['remove'] as $itemId => $from) {
                $this->removeItem($itemId, $from);
            }
        }
        if (isset($data['empty_customer_cart']) && (int)$data['empty_customer_cart'] == 1) {
            $this->getCustomerCart()->removeAllItems()->collectTotals()->save();
        }
        return $this;
    }

    /**
     * Remove quote item
     *
     * @param   int $item
     * @return  Mage_Adminhtml_Model_Sales_Order_Create
     */
    public function removeQuoteItem($item)
    {
        $this->getQuote()->removeItem($item);
        $this->setRecollect(true);
        return $this;
    }

    /**
     * Prepare options array for info buy request
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return array
     */
    protected function _prepareOptionsForRequest($item)
    {
        $newInfoOptions = array();
        if ($optionIds = $item->getOptionByCode('option_ids')) {
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                $option = $item->getProduct()->getOptionById($optionId);
                $optionValue = $item->getOptionByCode('option_'.$optionId)->getValue();

                $group = Mage::getSingleton('catalog/product_option')->groupFactory($option->getType())
                    ->setOption($option)
                    ->setQuoteItem($item);

                $newInfoOptions[$optionId] = $group->prepareOptionValueForRequest($optionValue);
            }
        }
        return $newInfoOptions;
    }

    protected function _parseCustomPrice($price)
    {
        $price = Mage::app()->getLocale()->getNumber($price);
        $price = $price>0 ? $price : 0;
        return $price;
    }

    /**
     * Retrieve oreder quote shipping address
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    /**
     * Return Customer (Checkout) Form instance
     *
     * @return Mage_Customer_Model_Form
     */
    protected function _getCustomerForm()
    {
        if (is_null($this->_customerForm)) {
            $this->_customerForm = Mage::getModel('customer/form')
                ->setFormCode('adminhtml_checkout')
                ->ignoreInvisible(false);
        }
        return $this->_customerForm;
    }

    /**
     * Return Customer Address Form instance
     *
     * @return Mage_Customer_Model_Form
     */
    protected function _getCustomerAddressForm()
    {
        if (is_null($this->_customerAddressForm)) {
            $this->_customerAddressForm = Mage::getModel('customer/form')
                ->setFormCode('adminhtml_customer_address')
                ->ignoreInvisible(false);
        }
        return $this->_customerAddressForm;
    }

    protected function _setQuoteAddress(Mage_Sales_Model_Quote_Address $address, array $data)
    {
        return $this;
    }

    public function setShippingAddress($address)
    {
        return $this;
    }

    public function setShippingAsBilling($flag)
    {
        return $this;
    }

    public function getBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    public function setBillingAddress($address)
    {
        return $this;
    }

    public function setShippingMethod($method)
    {
        return $this;
    }

    public function resetShippingMethod()
    {
        return $this;
    }

    /**
     * Collect shipping data for quote shipping address
     */
    public function collectShippingRates()
    {
        $this->getQuote()->getShippingAddress()->setCollectShippingRates(true);
        $this->collectRates();
        return $this;
    }

    public function setPaymentMethod($method)
    {
        return $this;
    }

    public function setPaymentData($data)
    {
        return $this;
    }

    public function applyCoupon($code)
    {
        return $this;
    }

    public function setAccountData($accountData)
    {
        return $this;
    }

    /**
     * Parse data retrieved from request
     *
     * @param   array $data
     * @return  Mage_Adminhtml_Model_Sales_Order_Create
     */
    public function importPostData($data)
    {
        if (is_array($data)) {
            $this->addData($data);
        } else {
            return $this;
        }

        if (isset($data['account'])) {
            $this->setAccountData($data['account']);
        }

        if (isset($data['comment'])) {
            $this->getQuote()->addData($data['comment']);
            if (empty($data['comment']['customer_note_notify'])) {
                $this->getQuote()->setCustomerNoteNotify(false);
            } else {
                $this->getQuote()->setCustomerNoteNotify(true);
            }
        }

        if (isset($data['payment_info'])) {
            $this->getQuote()->setPaymentInfo($data['payment_info']);
        }

        return $this;
    }


    /**
     * Set and validate Customer data
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Adminhtml_Model_Sales_Order_Create
     */
    protected function _setCustomerData(Mage_Customer_Model_Customer $customer)
    {
        $form = $this->_getCustomerForm();
        $form->setEntity($customer);

        // emulate request
        $request = $form->prepareRequest(array('order' => $this->getData()));
        $data    = $form->extractData($request, 'order/account');
        if ($this->getIsValidate()) {
            $errors = $form->validateData($data);
            if ($errors !== true) {
                foreach ($errors as $error) {
                    $this->_errors[] = $error;
                }
                $form->restoreData($data);
            } else {
                $form->compactData($data);
            }
        } else {
            $form->restoreData($data);
        }

        return $this;
    }


    /**
     * Create new order
     *
     * @return Mage_Sales_Model_Order
     */
    public function createOrder()
    {
        $this->_prepareCustomer();
        $this->_validate();
        $quote = $this->getQuote();
        $this->_prepareQuoteItems();

        $service = Mage::getModel('buyback/service_quote', $quote);

        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();

            foreach ($oldOrder->getAllItems() as $_item) {
                $_item->delete();
            }

            $originalId = $oldOrder->getOriginalIncrementId();
            if (!$originalId) {
                $originalId = $oldOrder->getIncrementId();
            }
            $orderData = array(
                'original_increment_id'     => $originalId,
                'relation_parent_id'        => $oldOrder->getId(),
                'relation_parent_real_id'   => $oldOrder->getIncrementId(),
                'edit_increment'            => $oldOrder->getEditIncrement(),  //+1,
                'increment_id'              => $originalId// .'-'.($oldOrder->getEditIncrement()+1)
            );


            $quote->setReservedOrderId($orderData['increment_id']);
            $service->setOrderData($orderData);
        }

        $order = $service->submit();
        if ((!$quote->getCustomer()->getId() || !$quote->getCustomer()->isInStore($this->getSession()->getStore()))
            && !$quote->getCustomerIsGuest()
        ) {
            $quote->getCustomer()->setCreatedAt($order->getCreatedAt());
            $quote->getCustomer()
                ->save()
                ->sendNewAccountEmail('registered', '', $quote->getStoreId());;
        }
        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();

            $order->setId($oldOrder->getId());
//            $order->save();
        }
        if ($this->getSendConfirmation()) {
            $order->sendNewOrderEmail();
        }

        Mage::dispatchEvent('buyback_checkout_submit_all_after', array('order' => $order, 'quote' => $quote));

        return $order;
    }


    protected function _validate()
    {
        $customerId = $this->getSession()->getCustomerId();
        if (is_null($customerId)) {
            Mage::throwException(Mage::helper('adminhtml')->__('Please select a customer.'));
        }

        if (!$this->getSession()->getStore()->getId()) {
            Mage::throwException(Mage::helper('adminhtml')->__('Please select a store.'));
        }
        $items = $this->getQuote()->getAllItems();

        if (count($items) == 0) {
            $this->_errors[] = Mage::helper('adminhtml')->__('You need to specify order items.');
        }

        foreach ($items as $item) {
            $messages = $item->getMessage(false);
            if ($item->getHasError() && is_array($messages) && !empty($messages)) {
                $this->_errors = array_merge($this->_errors, $messages);
            }
        }




        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $this->getSession()->addError($error);
            }
            Mage::throwException('');
        }
        return $this;
    }

    /**
     * Retrieve new customer email
     *
     * @param   Mage_Customer_Model_Customer $customer
     * @return  string
     */
    protected function _getNewCustomerEmail($customer)
    {
        $email = $this->getData('account/email');
        if (empty($email)) {
            $host = $this->getSession()
                ->getStore()
                ->getConfig(Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN);
            $account = $customer->getIncrementId() ? $customer->getIncrementId() : time();
            $email = $account.'@'. $host;
            $account = $this->getData('account');
            $account['email'] = $email;
            $this->setData('account', $account);
        }
        return $email;
    }

    public function updateQuoteItems($data)
    {
        if (is_array($data)) {
            try {
                foreach ($data as $itemId => $info) {
                    if (!empty($info['configured'])) {
                        $item = $this->getQuote()->updateItem($itemId, new Varien_Object($info));
                        $itemQty = (float)$item->getQty();
                    } else {
                        $item       = $this->getQuote()->getItemById($itemId);
                        $itemQty    = (float)$info['qty'];
                    }

                    if ($item) {
                        if ($item->getProduct()->getStockItem()) {
                            if (!$item->getProduct()->getStockItem()->getIsQtyDecimal()) {
                                $itemQty = (int)$itemQty;
                            } else {
                                $item->setIsQtyDecimal(1);
                            }
                        }

                        $itemQty    = $itemQty > 0 ? $itemQty : 1;
                        if (isset($info['custom_price'])) {
                            $itemPrice  = $this->_parseCustomPrice($info['custom_price']);
                        } else {
                            $itemPrice = null;
                        }
                        $noDiscount = !isset($info['use_discount']);

                        if (empty($info['action']) || !empty($info['configured'])) {
                            $item->setQty($itemQty);
                            $item->setCustomPrice($itemPrice);
                            $item->setOriginalCustomPrice($itemPrice);
                            $item->setNoDiscount($noDiscount);
                            $item->getProduct()->setIsSuperMode(true);
                            $item->getProduct()->unsSkipCheckRequiredOption();
                            $item->checkData();
                        }
                        if (!empty($info['action'])) {
                            $this->moveQuoteItem($item, $info['action'], $itemQty);
                        }
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $this->recollectCart();
                throw $e;
            } catch (Exception $e) {
                Mage::logException($e);
            }
            $this->recollectCart();
        }
        return $this;
    }

    public function addProduct($product, $config = 1)
    {
        if (!is_array($config) && !($config instanceof Varien_Object)) {
            $config = array('qty' => $config);
        }
        $config = new Varien_Object($config);

        if (!($product instanceof Mage_Catalog_Model_Product)) {
            $productId = $product;
            $product = Mage::getModel('catalog/product')
                ->setStore($this->getSession()->getStore())
                ->setStoreId($this->getSession()->getStoreId())
                ->load($product);
            if (!$product->getId()) {
                Mage::throwException(
                    Mage::helper('adminhtml')->__('Failed to add a product to cart by id "%s".', $productId)
                );
            }
        }

        $product->setCartQty($config->getQty());
        $item = $this->getQuote()->addProductAdvanced(
            $product,
            $config,
            Mage_Catalog_Model_Product_Type_Abstract::PROCESS_MODE_FULL
        );
        if (is_string($item)) {
            if ($product->getTypeId() != Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE) {
                $item = $this->getQuote()->addProductAdvanced(
                    $product,
                    $config,
                    Mage_Catalog_Model_Product_Type_Abstract::PROCESS_MODE_LITE
                );
            }
            if (is_string($item)) {
                Mage::throwException($item);
            }
        }
        $item->checkData();

        $this->setRecollect(true);
        return $this;
    }
}
