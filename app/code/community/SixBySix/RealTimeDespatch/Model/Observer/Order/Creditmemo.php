<?php

/**
 * Order Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Order_Creditmemo
{
    /**
     * Adds the OrderFlow block to the creditmemo pages
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addInfoBlock($event)
    {
	    if (!Mage::helper('realtimedespatch')->isEnabled() || !Mage::helper('realtimedespatch')->isConfigured()) {
		    return;
	    }

	    $request = Mage::app()->getRequest();
	    $block = $event->getBlock();
	    // sales > order > view
	    if ($block->getNameInLayout() == 'form' &&
		    ($request->getControllerName() == 'sales_order_creditmemo')) {
		    $transport = $event->getTransport();
		    $order = $block->getOrder();
		    if ($transport && !$order->getIsVirtual()) {
			    $html = $transport->getHtml();

			    // append orderflow info box
			    $layout = $block->getLayout();
			    $child = $layout->createBlock('realtimedespatch/adminhtml_sales_order_creditmemo')
				    ->setOrder($order);
			    $childHtml = $child->toHtml() . '<div class="clear"></div>';

			    $pattern = '#<div class="entry-edit">\s*<div class="entry-edit-head">\s*<h4 class="icon-head head-products">Items (to )?Refund(ed)?</h4>#i';
				$html = preg_replace($pattern, $childHtml . '$0', $html);

			    $transport->setHtml($html);
		    }
	    }
    }
}