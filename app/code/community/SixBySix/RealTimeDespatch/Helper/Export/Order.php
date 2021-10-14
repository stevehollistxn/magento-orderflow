<?php

/**
 * Order Export Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Export_Order extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the order export is enabled.
     *
     * @return boolean
     */
    public function isExportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('realtimedespatch/order_export/is_enabled');
    }

    /**
     * Checks whether the discount feature is enabled.
     *
     * @return boolean
     */
    public function isDiscountEnabled()
    {
        return Mage::getStoreConfigFlag('realtimedespatch/order_export/is_discount_enabled');
    }

    /**
     * Returns the current order export batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/order_export/batch_size');
    }

    /**
     * Returns the exportable order statuses.
     *
     * @return array
     */
    public function getExportableOrderStatuses()
    {
        return explode(',', Mage::getStoreConfig('realtimedespatch/order_export/exportable_order_statuses'));
    }

    /**
     * Returns a list of orders that can be exported.
     *
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    public function getExportableOrders()
    {
        return Mage::getResourceModel('sales/order_collection')
                    ->addFieldToFilter('status', array('in' => $this->getExportableOrderStatuses()))
                    ->addFieldToFilter('is_virtual', array('eq' => 0))
                    ->addFieldToFilter('is_exported', array('eq' => 0))
                    ->addFieldToFilter('export_failures', array('lt' => 4))
                    ->setPageSize($this->getBatchLimit())
                    ->setCurPage(1);
    }

    /**
     * Disables the order export cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('realtimedespatch/order_export/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the order export cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'orderflow_order_export' AND status = 'pending'");
    }
}