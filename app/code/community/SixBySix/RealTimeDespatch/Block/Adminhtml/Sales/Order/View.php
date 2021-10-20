<?php

class SixBySix_RealTimeDespatch_Block_Adminhtml_Sales_Order_View extends Mage_Core_Block_Template
{
	protected $_template = 'realtimedespatch/sales/order/view.phtml';

	public function getOrderFlowUrl()
	{
		return $this->helper()->getApiEndpoint() .
			'despatch/order/referenceDetail.htm?externalReference=' . urlencode($this->getOrder()->getIncrementId()) .
			'&channel=' . urlencode($this->helper()->getApiChannel());
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
