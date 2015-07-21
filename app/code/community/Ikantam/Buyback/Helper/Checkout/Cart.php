<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ikantam_Buyback_Helper_Checkout_Cart  extends Mage_Checkout_Helper_Cart
{
    public function getAddUrl($product, $additional = array())
    {
        $continueUrl    = Mage::helper('core')->urlEncode($this->getCurrentUrl());
        $urlParamName   = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;

        $routeParams = array(
            $urlParamName   => $continueUrl,
            'product'       => $product->getEntityId(),
            Mage_Core_Model_Url::FORM_KEY => $this->_getSingletonModel('core/session')->getFormKey()

        );

        if (!empty($additional)) {
            $routeParams = array_merge($routeParams, $additional);
        }

        if ($product->hasUrlDataObject()) {
            $routeParams['_store'] = $product->getUrlDataObject()->getStoreId();
            $routeParams['_store_to_url'] = true;
        }


        $routeName = $product->getTypeId() == 'buyback' ? 'buyback' : 'checkout';


        if ($this->_getRequest()->getRouteName() == $routeName
            && $this->_getRequest()->getControllerName() == 'cart') {
            $routeParams['in_cart'] = 1;
        }

        return $this->_getUrl($routeName . '/cart/add', $routeParams);
    }

}
