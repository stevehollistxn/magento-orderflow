<?php

require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

/**
 * Adminhtml Sales Order Controller
 */
class SixBySix_RealTimeDespatch_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    /**
     * Exports a set of orders to Orderflow.
     *
     * @return void
     */
    public function exportToOrderFlowAction()
    {
    	$orderIds = $this->getRequest()->getParam('order_ids');
    	if (!is_array($orderIds)) {
		    $this->_getSession()->addError($this->__('Error Exporting Order(s) to OrderFlow: No orders selected.'));
		    $this->_redirect('*/*/index');
		    return $this;
	    }

        $orders = Mage::getResourceModel('sales/order_collection')
	            ->addFieldToFilter('entity_id', array('in' => $orderIds))
                ->addFieldToFilter('is_virtual', array('eq' => 0))
                ->addFieldToFilter('status', array('in' => Mage::helper('realtimedespatch/export_order')->getExportableOrderStatuses()))
                ->load();

        if ( ! count($orders) > 0) {
            $this->_getSession()->addError($this->__('Error Exporting Order(s) to OrderFlow: No exportable orders selected.'));
            $this->_redirect('*/*/index');
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_ORDER_EXPORT);

        try
        {
            $report = $service->export($orders, true);

            $this->_getSession()->addSuccess(
                $this->__('Exported '.count($orders).' orders(s) to OrderFlow with '.$report->getNumberOfFailures().' failures.')
            );
        }
        catch (Exception $ex)
        {
            $this->_getSession()->addError($this->__('Error Exporting Order(s) to OrderFlow: '.$ex->getMessage()));
        }

        $this->_redirect('*/*/index');
    }
}