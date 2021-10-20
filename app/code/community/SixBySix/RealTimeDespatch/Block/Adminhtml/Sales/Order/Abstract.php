<?php

abstract class SixBySix_RealTimeDespatch_Block_Adminhtml_Sales_Order_Abstract extends Mage_Core_Block_Template
{
	public function getOrderFlowUrl()
	{
		return $this->helper()->getApiEndpoint() .
			'despatch/order/referenceDetail.htm?externalReference=' . urlencode($this->getOrder()->getIncrementId()) .
			'&channel=' . urlencode($this->helper()->getApiChannel());
	}

	public function isExported()
	{
		return (boolean) $this->getOrder()->getIsExported();
	}

	public function getExportedAt()
	{
		$exportedAt = $this->getOrder()->getExportedAt();
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

	public function setOrder(Mage_Sales_Model_Order $order)
	{
		$this->setData('order', $order);
		return $this;
	}

	public function getSide()
	{
		if (!$this->hasData('side')) {
			$this->setData('side', 'left');
		}

		return $this->getData('side');
	}

	public function helper()
	{
		return Mage::helper('realtimedespatch');
	}
}
