<?php

/**
 * Process Executed With Items Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Process_Executed_With_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        $executed = $this->_getValue($row);

        if ($executed) {
            return parent::render($row);
        }

        // @todo Untested
        $lastSchedule = Mage::getResourceModel('realtimedespatch/process_schedule_collection')
	        ->getLastScheduleWithItems($row->getEntity());

        if ($lastSchedule->getId()) {
	        $row->setData($this->getColumn()->getIndex(), $lastSchedule->getExecutedWithItems());
	        return parent::render($row);
        }

        return 'Pending';
    }
}