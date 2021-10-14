<?php

/**
 * Product Gridg Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Product_Edit_Attributes
{
    /**
     * Add HTML block as prefix to tab content.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addPrefixToTab($event)
    {
        $block = $event->getBlock();

	    if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes) {
		    $group = $block->getGroup();
		    if ($group && $group->getAttributeGroupName() == 'OrderFlow') {
			    $prefixBlock = Mage::app()->getLayout()->createBlock(
				    'Mage_Core_Block_Template',
				    'orderflow_info',
				    array('template' => 'realtimedespatch/catalog/product/tab/orderflow.phtml')
			    );


			    $transport = $event->getTransport();
			    $html = $transport->getHtml();
			    $transport->setHtml($prefixBlock->toHtml() . $html);
		    }
	    }
    }

}