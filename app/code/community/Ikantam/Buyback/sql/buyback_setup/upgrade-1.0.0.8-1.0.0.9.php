<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->getConnection()
    ->addColumn($installer->getTable('buyback/order_item'), 'product_id', 'int(10) unsigned');