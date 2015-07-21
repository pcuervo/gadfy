<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/quote'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Store Id')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'on update' => 'CURRENT_TIMESTAMP',
        'default'   => 'CURRENT_TIMESTAMP'
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('converted_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => true,
    ), 'Converted At')

    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 1
    ), 'Is Active')

    ->addColumn('items_count', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Items Count')

    ->addColumn('items_qty', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Items Qty')

    ->addColumn('orig_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Orig Order Id')

    ->addColumn('base_currency_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Base Currency Code')

    ->addColumn('store_currency_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Store Currency Code')

    ->addColumn('quote_currency_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Quote Currency Code')

    ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Grand Total')

    ->addColumn('base_grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Base Grand Total')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Customer Id')

    ->addColumn('customer_tax_class_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Customer Tax Class Id')

    ->addColumn('customer_group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Customer Group Id')

    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Email')

    ->addColumn('customer_prefix', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Prefix')

    ->addColumn('customer_firstname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Firstname')

    ->addColumn('customer_middlename', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Middlename')

    ->addColumn('customer_lastname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Lastname')

    ->addColumn('customer_suffix', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Suffix')

    ->addColumn('customer_dob', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => true,
    ), 'Customer Dob')

    ->addColumn('customer_note', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Note')

    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Email')

    ->addColumn('customer_note_notify', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Customer Note Notify')

    ->addColumn('customer_is_guest', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Customer Is Guest')

    ->addColumn('remote_ip', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true,
    ), 'Remote Ip')

    ->addColumn('password_hash', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Password Hash')

    ->addColumn('global_currency_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Global Currency Code')

    ->addColumn('customer_taxvat', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Taxvat')

    ->addColumn('customer_gender', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Gender')

    ->addColumn('subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Subtotal')

    ->addColumn('base_subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base subtotal')

    ->addColumn('is_changed', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true
    ), 'Is Changed')

    ->addColumn('trigger_recollect', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'default'   => 0
    ), 'Trigger Recollect')

    ->addColumn('gift_message_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true
    ), 'Gift Message Id')

    ->addColumn('is_persistent', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'Is Persistent')

    ->addColumn('payment_info',  Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Payment Info')
    ->setComment('Ikantam Buyback Quote');

$installer->getConnection()->createTable($table);

$installer->getConnection()
    ->addIndex($installer->getTable('buyback/quote'), $installer->getIdxName('buyback/quote', array('store_id')), array('store_id'));
$installer->getConnection()
    ->addForeignKey(
        $installer->getFkName(
            'buyback/quote',
            'store_id',
            'core_store',
            'store_id'
        ),
        $installer->getTable('buyback/quote'),
        'store_id',
        $installer->getTable('core_store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);



/*******************quote_item****************************************************************************/

$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/quote_item'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'primary'   => true,
    ), 'Item Id')

    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'default'   => 0
    ), 'Order Id')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Product Id')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Store Id')

    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Sku')

    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Name')

    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Description')

    ->addColumn('additional_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Additional Data')

    ->addColumn('is_qty_decimal', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true
    ), 'Is Qty Decimal')

    ->addColumn('weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Weight')

    ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Qty')

    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Price')

    ->addColumn('base_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Base Price')

    ->addColumn('custom_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Custom Price')

    ->addColumn('tax_percent', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Tax Percent')

    ->addColumn('tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Tax Amount')

    ->addColumn('base_tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Base Tax Amount')

    ->addColumn('row_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Row Total')

    ->addColumn('base_row_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Base Row Total')

    ->addColumn('row_weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Row Weight')

    ->addColumn('product_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true
    ), 'Product Type')

    ->addColumn('original_custom_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Original Custom Price')

    ->addColumn('redirect_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true
    ), 'Redirect Url')

    ->addColumn('base_cost', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Cost')

    ->addColumn('price_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Price Incl Tax')

    ->addColumn('base_price_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Price Incl Tax')

    ->addColumn('row_total_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Row Total Incl Tax')

    ->addColumn('base_row_total_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Row Total Incl Tax')

    ->setComment('Ikantam Buyback Quote Item');

$installer->getConnection()->createTable($table);

$installer->getConnection()
    ->addIndex($installer->getTable('buyback/quote_item'), $installer->getIdxName('buyback/quote_item', array('quote_id', 'product_id', 'store_id')), array('quote_id', 'product_id', 'store_id'));


$installer->getConnection()
    ->addForeignKey(
        $installer->getFkName('buyback/quote_item', 'quote_id', 'buyback/quote', 'entity_id'),
        $installer->getTable('buyback/quote_item'),
        'quote_id',
        $installer->getTable('buyback/quote'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);


$installer->getConnection()
    ->addForeignKey($installer->getFkName('buyback/quote_item','quote_id','buyback/quote','entity_id'),
        $installer->getTable('buyback/quote_item'),
        'quote_id',
        $installer->getTable('buyback/quote'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);


$installer->getConnection()
    ->addForeignKey(
        $installer->getFkName('buyback/quote_item','product_id','catalog/product','entity_id'),
        $installer->getTable('buyback/quote_item'),
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$installer->getConnection()
    ->addForeignKey(
        $installer->getFkName('buyback/quote_item','store_id','core_store','store_id'),
        $installer->getTable('buyback/quote_item'),
        'store_id',
        $installer->getTable('core_store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE);


/**************************quote_item_option************************************************************* */

$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/quote_item_option'))
    ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'nullable'  => false,
        'identity'  => true,
        'unsigned'  => true,
        'primary'   => true,
    ), 'Option Id')

    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
    ), 'Item Id')

    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'nullable'  => false,
    ), 'Product Id')


    ->addColumn('code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Code')

    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Value')
    ->setComment('Ikantam Buyback Quote Item Option');

$installer->getConnection()->createTable($table);

$installer->getConnection()
    ->addIndex($installer->getTable('buyback/quote_item_option'), $installer->getIdxName('buyback/quote_item_option', array('item_id')), array('item_id'));


$installer->getConnection()
    ->addForeignKey($installer->getFkName('buyback/quote_item_option', 'item_id', 'buyback/quote_item', 'item_id'),
        $installer->getTable('buyback/quote_item_option'),
        'item_id',
        $installer->getTable('buyback/quote_item'),
        'item_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);


/*************** order ***************************************************************************/


$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')

    ->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Increment Id')

    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'State')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'Status')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Store Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Customer Id')

    ->addColumn('base_grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base Grand Total')

    ->addColumn('base_subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base Subtotal')

    ->addColumn('base_tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base Tax Amount')

    ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Grand Total')

    ->addColumn('subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Subtotal')

    ->addColumn('tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Tax Amount')

    ->addColumn('customer_is_guest', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Customer Is Guest')

    ->addColumn('email_sent', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true,
    ), 'Email Sent')

    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true,
    ), 'Quote Id')

    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'customer_email')



    ->addColumn('customer_firstname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'customer_firstname')

    ->addColumn('customer_lastname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'customer_lastname')

    ->addColumn('customer_middlename', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'customer_middlename')

    ->addColumn('remote_ip', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'remote_ip')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('total_item_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Total Item Count')

    ->addColumn('payment_info', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    ), 'Payment Info')

    ->addColumn('billing_address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true,
    ), 'billing_address_id')

    ->addColumn('shipping_address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true,
    ), 'shipping_address_id')

    ->addColumn('address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable'  => true,
    ), 'address_id')


    ->setComment('Buyback Orders');

$installer->getConnection()->createTable($table);


/********** order item ************************************************************************/
$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/order_item'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'primary'   => true,
    ), 'Item Id')

    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'default'   => 0
    ), 'Order Id')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('product_options', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Product Options')

    ->addColumn('additional_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Additional Data')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Store Id')

    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Sku')

    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Name')

    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Description')

    ->addColumn('additional_data', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Additional Data')

    ->addColumn('is_qty_decimal', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true
    ), 'Is Qty Decimal')

    ->addColumn('weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Weight')

    ->addColumn('qty_ordered', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Qty Ordered')

    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Price')

    ->addColumn('base_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Base Price')

    ->addColumn('custom_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Custom Price')

    ->addColumn('tax_percent', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Tax Percent')

    ->addColumn('tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Tax Amount')

    ->addColumn('base_tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Base Tax Amount')

    ->addColumn('row_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Row Total')

    ->addColumn('base_row_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'default'   => 0.0000
    ), 'Base Row Total')

    ->addColumn('row_weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Row Weight')

    ->addColumn('product_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true
    ), 'Product Type')

    ->addColumn('original_custom_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Original Custom Price')

    ->addColumn('redirect_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true
    ), 'Redirect Url')

    ->addColumn('base_cost', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Cost')

    ->addColumn('price_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Price Incl Tax')

    ->addColumn('base_price_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Price Incl Tax')

    ->addColumn('row_total_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Row Total Incl Tax')

    ->addColumn('base_row_total_incl_tax', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12, 4), array(
        'nullable'  => true
    ), 'Base Row Total Incl Tax')

    ->setComment('Ikantam Buyback Order Item');

$installer->getConnection()->createTable($table);


/************** order_status_history *******************************************************************/

$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/order_status_history'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')

    ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'nullable'  => false,
    ), 'parent_id')


    ->addColumn('is_customer_notified', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'is_customer_notified')


    ->addColumn('is_visible_on_front', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'default'   => 0
    ), 'is_visible_on_front')



    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'comment')


    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true,
    ), 'status')


    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'on update' => 'CURRENT_TIMESTAMP',
        'default'   => 'CURRENT_TIMESTAMP'
    ), 'Created At')
    ->addColumn('entity_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true,
    ), 'entity_name')


    ->setComment('Ikantam Buyback Order Status History');

$installer->getConnection()->createTable($table);


/**************************************************/

$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/quote_address'))
    ->addColumn('address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')

    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Quote Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Customer Id')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'on update' => 'CURRENT_TIMESTAMP',
        'default'   => 'CURRENT_TIMESTAMP'
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('address_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 128, array(
        'nullable'  => true,
    ))
    ->addColumn('save_in_address_book', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'save_in_address_book')

    ->addColumn('customer_address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'customer_address_id')
    ->addColumn('collect_shipping_rates', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => 0
    ), 'collect_shipping_rates')



    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Email')

    ->addColumn('prefix', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Prefix')

    ->addColumn('firstname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Firstname')

    ->addColumn('middlename', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Middlename')

    ->addColumn('lastname', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => true,
    ), 'Customer Lastname')

    ->addColumn('suffix', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
        'nullable'  => true,
    ), 'Customer Suffix')

    ->addColumn('subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Subtotal')

    ->addColumn('base_subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base subtotal')



    ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Grand Total')

    ->addColumn('base_grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
        'default'   => 0.0000
    ), 'Base Grand Total')


    ->setComment('Ikantam Buyback Quote Address');

$installer->getConnection()->createTable($table);



/********* order_grid *****************************************************/
$installer->getConnection();

$table = $installer->getConnection()
    ->newTable($installer->getTable('buyback/order_grid'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')

    ->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Increment Id')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => true
    ), 'Status')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Store Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
    ), 'Customer Id')

    ->addColumn('base_grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base Grand Total')

    ->addColumn('base_subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Base Subtotal')

    ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Grand Total')

    ->addColumn('subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => true,
    ), 'Subtotal')


    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')

    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default'   => '0000-00-00 00:00:00'
    ), 'Updated At')

    ->addColumn('billing_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1, array(
        'nullable'  => true,
    ), 'Billing Name')

    ->addColumn('shipping_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1, array(
        'nullable'  => true,
    ), 'Shipping Name')

    ->setComment('Buyback Orders Grid');

$installer->getConnection()->createTable($table);
