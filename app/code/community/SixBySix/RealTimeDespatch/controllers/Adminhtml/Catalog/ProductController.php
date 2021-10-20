<?php

require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

/**
 * Adminhtml Catalog Product Controller
 */
class SixBySix_RealTimeDespatch_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * Generates a Google Product Feed.
     *
     * @return void
     */
    public function exportToOrderFlowAction()
    {
        $productIds = $this->getRequest()->getParam('product');
	    if (!is_array($productIds)) {
		    $this->_getSession()->addError($this->__('Error Exporting Product(s) to OrderFlow: No products selected.'));
		    $this->_redirect('*/*/index');
		    return $this;
	    }

        $products = Mage::getModel('catalog/product')
	            ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', $productIds)
                ->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
                ->addStoreFilter(Mage::helper('realtimedespatch/export_product')->getStoreId())
                ->load();

        if ( ! count($products) > 0) {
        	$this->_getSession()->addError(
        		$this->__('Error Exporting Product(s) to OrderFlow: No Simple products found in selection.')
	        );
            $this->_redirect('*/*/index');
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_PRODUCT_EXPORT);

        try
        {
            $report = $service->export($products, true);

            $this->_getSession()->addSuccess(
                $this->__('Exported '.count($products).' product(s) to OrderFlow with '.$report->getNumberOfFailures().' failures.')
            );
        }
        catch (Exception $ex)
        {
            $this->_getSession()->addError($this->__('Error Exporting Product(s) to OrderFlow: '.$ex->getMessage()));
        }

        $this->_redirect('*/*/index');
    }
}