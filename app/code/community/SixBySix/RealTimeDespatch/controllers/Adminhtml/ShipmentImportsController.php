<?php

/**
 * Shipment Imports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_ShipmentImportsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/imports/shipmentimports');
    }

    /**
     * Displays a list of all imports.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Shipment Imports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('imports')
             ->setOperationType('Import')
             ->setEntityType('Shipment')
             ->setReferenceLabel('Unique Shipment IDs');

        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }

    /**
     * Displays a single import.
     *
     * @return void
     */
    public function viewAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $import = Mage::getModel('realtimedespatch/import')->load($id);

	    if (!$import->getId()) {
		    $this->_getSession()->addError($this->__('Import does not exist.'));

		    $this->_redirect('*/*/index');
		    return $this;
	    }

        $this->loadLayout();

        $this->getLayout()
             ->getBlock('view')
             ->setImport($import)
             ->setOperationType('Import')
             ->setEntityType('Shipment')
             ->setReferenceLabel('Shipment ID');

        $this->_title($this->__('Shipment Import '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }
}