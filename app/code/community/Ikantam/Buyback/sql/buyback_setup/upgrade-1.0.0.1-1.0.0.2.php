<?php

$statuses = array(
    array(
      'status' => 'waiting_for_reception',
      'label'  => 'Waiting for reception'
    ),
    array(
        'status' => 'received',
        'label'  => 'Received'
    ),
    array(
        'status' => 'under_review',
        'label'  => 'Under review'
    ),
    array(
        'status' => 'reviewed',
        'label'  => 'Reviewed'
    ),
    array(
        'status' => 'paid',
        'label'  => 'Paid'
    ),
    array(
        'status' => 'needs_to_be_returned',
        'label'  => 'Needs to be returned'
    ),
    array(
        'status' => 'shipped_to_customer',
        'label'  => 'Shipped to Customer'
    )


);

foreach ($statuses as $_statusData) {

    /* @var $status Mage_Sales_Model_Order_Status */
$status = Mage::getModel('sales/order_status')->load($_statusData['status']);
    if (!$status->getStatus()) {
        $status->setData($_statusData);
        $status->save();
        $status->assignState('buyback', false);
    }
}

$installer = $this;
/**
 * Create product attribute 'associated_buyback_product'
 */
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'associated_buyback_product', array(
    'type'              => 'int',
    'label'             => 'Associated Buyback Product',
    'input'             => 'select',
    'source'            => 'buyback/product_attribute_source_buyback',
    'required'          => false,
    'sort_order'        => 6,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'apply_to'          => 'simple,configurable,virtual,downloadable',
    'group'             => 'General',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'user_defined'      => true
));
