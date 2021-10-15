<?php

/**
 * Import Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Import_Grid
{
    /**
     * Adds the order ID column to the imports grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addOrderIdColumn($event)
    {
        $block = $event->getBlock();

        if ($block->getEntityType() !== 'Shipment') {
            return;
        }

        $block->addColumnAfter(
            'order_id',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Unique Order IDs'),
                'align'    =>'left',
                'index'    => 'message',
                'renderer' => 'realtimedespatch/adminhtml_renderer_total_imports',
            ),
            'message_id'
        );

        $block->sortColumnsByOrder();
    }
}