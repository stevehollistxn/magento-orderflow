<?php

/**
 * Product Gridg Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Product_Edit_Tabs
{
    /**
     * Add HTML block as prefix to tab content.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addPrefixToTabs($event)
    {
        $block = $event->getBlock();

	    if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes) {
		    $group = $block->getGroup();
		    if ($group && $group->getAttributeGroupName() == 'OrderFlow') {
			    $prefixBlock = Mage::app()->getLayout()->createBlock(
				    'realtimedespatch/adminhtml_catalog_product_orderflow',
				    'orderflow_info',
				    array('template' => 'realtimedespatch/catalog/product/tab/orderflow.phtml')
			    );
		    }
	    }
	    if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory) {
		    $prefixBlock = Mage::app()->getLayout()->createBlock(
			    'realtimedespatch/adminhtml_catalog_product_inventory',
			    'orderflow_inventory',
			    array('template' => 'realtimedespatch/catalog/product/tab/inventory.phtml')
		    );
	    }
	    if (isset($prefixBlock)) {
		    $product = Mage::registry('product');
		    $prefixBlock->setProduct($product);
		    $transport = $event->getTransport();
		    $html = $transport->getHtml();
		    $transport->setHtml($prefixBlock->toHtml() . $html);
	    }
    }

}