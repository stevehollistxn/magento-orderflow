<?php

/**
 * Product Gridg Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Product_Edit_Inventory
{
    /**
     * Update template for product edit inventory tab.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function updateInventoryTabTemplate($event)
    {
        $block = $event->getBlock();

	    if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory) {
		    $block->setTemplate('realtimedespatch/catalog/product/tab/inventory.phtml');
	    }
    }
}