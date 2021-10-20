<?php

class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Orderflow extends Mage_Adminhtml_Block_Template
{
	protected $_template = 'realtimedespatch/catalog/product/tab/orderflow.phtml';

	public function getOrderFlowProductUrl()
	{
		if (!$this->getProduct()) {
			return '';
		}

		return $this->helper()->getApiEndpoint() .
			'inventory/product/referenceDetail.htm?externalReference=' . urlencode($this->getProduct()->getSku()) .
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
