<?php

/**
 * Process Executed Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Process_Executed extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        if ( ! $this->_getValue($row)) {
            return 'Pending';
        }

        return parent::render($row);
    }
}