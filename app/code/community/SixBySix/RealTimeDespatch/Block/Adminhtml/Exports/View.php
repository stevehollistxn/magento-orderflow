<?php

/**
 * Exports Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports_View extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_exports_view';

        parent::__construct();

        $this->_removeButton('edit');
    }

	/**
	 * @return Mage_Core_Block_Abstract
	 */
    protected function _prepareLayout()
	{
		$this->setChild('plane', $this->getLayout()->createBlock('realtimedespatch/' . $this->_controller . '_view_plane'));
		return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
	}

    /**
     * {@inheritdoc}
     */
    public function getHeaderText()
    {
        return Mage::helper('realtimedespatch')->__($this->getExport()->getEntityType().' Export #'.$this->getExport()->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->getChild('plane')
            ->setExport($this->getExport())
            ->setReferenceLabel($this->getReferenceLabel())
            ->setChild('lines', $this->getLayout()->createBlock('realtimedespatch/adminhtml_exports_lines_grid'));

        return parent::_toHtml();
    }
}