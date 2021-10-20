<?php

class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Inventory extends Mage_Adminhtml_Block_Template
{
	protected $_template = 'realtimedespatch/catalog/product/tab/inventory.phtml';

	public function getOrderFlowInventoryUrl()
	{
		if (!$this->getProduct()) {
			return '';
		}

		return $this->helper()->getApiEndpoint() .
			'inventory/inventory/referenceDetail.htm?externalReference=' . urlencode($this->getProduct()->getSku()) .
			'&channel=' . urlencode($this->helper()->getApiChannel());
	}

	protected function _toHtml()
	{
		if (!$this->helper()->isEnabled() || !$this->helper()->isConfigured()) {
			return '';
		}

		return parent::_toHtml();
	}

	public function setProduct(Mage_Catalog_Model_Product $product)
	{
		$this->setData('product', $product);
		return $this;
	}

	public function helper()
	{
		return Mage::helper('realtimedespatch');
	}
}
