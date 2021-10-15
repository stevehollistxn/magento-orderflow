<?php

/**
 * Order Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Order_Grid
{
    /**
     * Adds the export column to the orders grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportColumn($event)
    {
        $block = $event->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid &&
	        Mage::helper('realtimedespatch/admin_info')->showExportedColumnOnOrderGrid()) {
	        $block->addColumnAfter(
		        'is_exported',
		        array(
			        'header' => Mage::helper('realtimedespatch')->__('Exported To OrderFlow'),
			        'width' => '80px',
			        'index' => 'is_exported',
			        'type' => 'options',
			        'options' => array(1 => 'True', 0 => 'False'),
			        'renderer' => 'realtimedespatch/adminhtml_renderer_exported',
			        'align' => 'center'
		        ),
		        'status'
	        );

	        $block->sortColumnsByOrder();
        }
    }

    /**
     * Adds the export action to the orders grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportAction($event)
    {
        if ( ! Mage::helper('realtimedespatch/export_order')->isExportEnabled()){
            return;
        }

        $block = $event->getBlock();
	    if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction &&
		    $block->getRequest()->getControllerName() == 'sales_order') {
		    $block->addItem(
			    'export',
			    array(
				    'label' => Mage::helper('sales')->__('Export To OrderFlow'),
				    'url' => $block->getUrl('*/*/exportToOrderFlow'),
				    'confirm' => Mage::helper('sales')->__('Are you sure?')
			    ));
	    }
    }
}