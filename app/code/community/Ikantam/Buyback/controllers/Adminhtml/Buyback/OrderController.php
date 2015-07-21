<?php

class  Ikantam_Buyback_Adminhtml_Buyback_OrderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = array('view', 'index');

    /**
     * Additional initialization
     *
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Ikantam_Buyback');
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('buyback/order')
            ->_addBreadcrumb($this->__('Buyback'), $this->__('Buyback'))
            ->_addBreadcrumb($this->__('Orders'), $this->__('Orders'));
        return $this;
    }

    /**
     * Initialize order model instance
     *
     * @return Ikantam_Buyback_Model_Order || false
     */
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('buyback/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('sales_order', $order);
        Mage::register('buyback_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }

    /**
     * Orders grid
     */
    public function indexAction()
    {
        $this->_title($this->__('Buyback'))->_title($this->__('Buyback Orders'));

        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Order grid
     */
    public function gridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function customergridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * View order detale
     */
    public function viewAction()
    {
        $this->_title($this->__('Buyback'))->_title($this->__('Orders'));

        if ($order = $this->_initOrder()) {
            $this->_initAction();

            $this->_title(sprintf("#%s", $order->getRealOrderId()));

            $this->renderLayout();
        }
    }

    /**
     * Notify user
     */
    public function emailAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->sendNewOrderEmail();
                $historyItem = Mage::getResourceModel('buyback/order_status_history_collection')
                    ->getUnnotifiedForInstance($order, Mage_Sales_Model_Order::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                $this->_getSession()->addSuccess($this->__('The order email has been sent.'));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Failed to send the order email.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/buyback_order/view', array('order_id' => $order->getId()));
    }
    /**
     * Cancel order
     */
    public function cancelAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->cancel()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been cancelled.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order has not been cancelled.'));
                Mage::logException($e);
            }
            $this->_redirect('*/buyback_order/view', array('order_id' => $order->getId()));
        }
    }

    /**
     * Hold order
     */
    public function holdAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->hold()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been put on hold.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order was not put on hold.'));
            }
            $this->_redirect('*/buyback_order/view', array('order_id' => $order->getId()));
        }
    }

    /**
     * Unhold order
     */
    public function unholdAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->unhold()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been released from holding status.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order was not unheld.'));
            }
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        }
    }


    /**
     * Add order comment action
     */
    public function addCommentAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $response = false;
                $data = $this->getRequest()->getPost('history');
                $notify = isset($data['is_customer_notified']) ? $data['is_customer_notified'] : false;
                $visible = isset($data['is_visible_on_front']) ? $data['is_visible_on_front'] : false;

                $order->addStatusHistoryComment($data['comment'], $data['status'])
                    ->setIsVisibleOnFront($visible)
                    ->setIsCustomerNotified($notify)->save();

                $comment = trim(strip_tags($data['comment']));
                $order->save();
                $order->sendOrderUpdateEmail($notify, $comment);

                $this->loadLayout('empty');
                $this->renderLayout();
            }
            catch (Mage_Core_Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $e->getMessage(),
                );
            }
            catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot add order history.')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }



    /**
     * Generate order history for ajax request
     */
    public function commentsHistoryAction()
    {
        $this->_initOrder();
        $html = $this->getLayout()->createBlock('adminhtml/sales_order_view_tab_history')->toHtml();
        /* @var $translate Mage_Core_Model_Translate_Inline */
        $translate = Mage::getModel('core/translate_inline');
        if ($translate->isAllowed()) {
            $translate->processResponseBody($html);
        }
        $this->getResponse()->setBody($html);
    }




    /**
     * Acl check for admin
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'hold':
                $aclResource = 'buyback/order/actions/hold';
                break;
            case 'unhold':
                $aclResource = 'buyback/order/actions/unhold';
                break;
            case 'email':
                $aclResource = 'buyback/order/actions/email';
                break;
            case 'cancel':
                $aclResource = 'buyback/order/actions/cancel';
                break;
            case 'view':
                $aclResource = 'buyback/order/actions/view';
                break;
            case 'addcomment':
                $aclResource = 'buyback/order/actions/comment';
                break;
            case 'creditmemos':
                $aclResource = 'buyback/order/actions/creditmemo';
                break;
            case 'reviewpayment':
                $aclResource = 'buyback/order/actions/review_payment';
                break;
            default:
                $aclResource = 'buyback/order';
                break;

        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'orders.csv';
        $grid       = $this->getLayout()->createBlock('adminhtml/sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }


    /**
     * Order transactions grid ajax action
     *
     */
    public function transactionsAction()
    {
        $this->_initOrder();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $order = Mage::getModel('buyback/order')->load($this->getRequest()->getParam('order_id'));
        try {
            if ($order->getId()) {
                $order->delete();
            } else {
                Mage::throwException('Order not found');
            }
            Mage::getSingleton('adminhtml/session')->addSuccess('Order has been deleted');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('adminhtml/buyback_order');
    }

}
