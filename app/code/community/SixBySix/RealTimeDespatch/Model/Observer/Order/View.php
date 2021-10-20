<?php

/**
 * Order Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Order_View
{
    /**
     * Adds the OrderFlow block to the order view page
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
	    if ($block->getNameInLayout() == 'order_info' &&
		    ($request->getControllerName() == 'sales_order' && $request->getActionName() == 'view')) {
		    $transport = $event->getTransport();
		    $order = $block->getOrder();
		    if ($transport && !$order->getIsVirtual()) {
			    $html = $transport->getHtml();

			    // append orderflow info box
			    $layout = $block->getLayout();
			    $child = $layout->createBlock('realtimedespatch/adminhtml_sales_order_view')
				    ->setOrder($order);
			    // count the number of boxes so far
			    $side = preg_match_all('/box-(left|right)/i', $html) % 2 == 0 ? 'left' : 'right';
			    $child->setSide($side);
			    if ($side == 'right') {
				    $html = preg_replace('/<div class="clear">\s?<\/div>\s?$/i', '', $html);
			    }
			    $html .= $child->toHtml();
			    if ($side == 'right') {
				    $html .= '<div class="clear"></div>';
			    }

			    $transport->setHtml($html);
		    }
	    }
    }
}