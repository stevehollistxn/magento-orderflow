<?php

/* @var $installer SixBySix_RealTimeDespatch_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

// Disable Symlinks - a security risk and was only required for composer version of Varien_Autoload
Mage::getConfig()->saveConfig('dev/template/allow_symlink', '0', 'default', 0);

// Update core_config_data with updated config paths
$collection = Mage::getModel('core/config_data')
	->getCollection()
	->addFieldToFilter('path', array('like' => 'sixbysix_realtimedespatch/%'));

foreach ($collection as $config) {
	// update prefix
	$config->setPath(
		str_replace('sixbysix_realtimedespatch/', 'realtimedespatch/', $config->getPath())
	);
	// move api_config to general
	if (strpos($config->getPath(), '/api_config/') !== false) {
		$config->setPath(
			str_replace('/api_config/', '/general/', $config->getPath())
		);
	}
	$config->save();
}

// remove Varien_Db_Ddl_Table::TIMESTAMP_INIT [CURRENT_TIMESTAMP()] default from 'created' columns
// the value of this depends on MySQL configuration so produces inconsistent results with timezones
// default value of created now set in _beforeSave method of resource model as per usual Magento approach
$connection = $installer->getConnection();
$tables = array(
	'export',
	'export_line',
	'import',
	'import_line',
	'process_schedule',
	'request',
	'request_line'
);
foreach ($tables as $table) {
	$connection->modifyColumn(
		$installer->getTable('realtimedespatch/' . $table),
		'created',
		array(
			'type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
			'nullable' => false,
			'default' => '0000-00-00 00:00:00'
		)
	);
}

// remove unused order attributes/attribute group
foreach (array('is_exported', 'exported_at', 'export_failures') as $attribute) {
	$installer->removeAttribute(Mage_Sales_Model_Order::ENTITY, $attribute);
}
$attributeSetId = $this->getAttributeSetId(Mage_Sales_Model_Order::ENTITY, 'Default');
$installer->removeAttributeGroup(Mage_Sales_Model_Order::ENTITY, $attributeSetId, 'OrderFlow');

$installer->endSetup();