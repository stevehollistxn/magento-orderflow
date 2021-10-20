<?php

abstract class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Abstract extends Mage_Adminhtml_Block_Template
{
	public function getOrderFlowInventoryUrl()
	{
		if (!$this->getProduct()) {
			return '';
		}

		return $this->helper()->getApiEndpoint() .
			'inventory/inventory/referenceDetail.htm?externalReference=' . urlencode($this->getProduct()->getSku()) .
			'&channel=' . urlencode($this->helper()->getApiChannel());
	}

	public function getOrderFlowProductUrl()
	{
		if (!$this->getProduct()) {
			return '';
		}

		return $this->helper()->getApiEndpoint() .
			'inventory/product/referenceDetail.htm?externalReference=' . urlencode($this->getProduct()->getSku()) .
			'&channel=' . urlencode($this->helper()->getApiChannel());
	}

	public function isExported()
	{
		return (boolean) $this->getProduct()->getIsExported();
	}

	public function getExportedAt()
	{
		$exportedAt = $this->getProduct()->getExportedAt();
		if (!$exportedAt) {
			return '';
		}

		return Mage::helper('core')->formatDate($exportedAt, 'medium', true);
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
