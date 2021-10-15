<?php

$installer = $this;
$installer->startSetup();

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

$installer->endSetup();