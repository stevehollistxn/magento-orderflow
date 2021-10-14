<?php

/**
 * Inventory Import Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Import_Inventory extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the inventory import is enabled.
     *
     * @return boolean
     */
    public function isImportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('realtimedespatch/inventory_import/is_enabled');
    }

    /**
     * Returns the current inventory import batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/batch_size');
    }

    /**
     * Returns a list of importable inventory requests.
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Collection
     */
    public function getImportableRequests()
    {
        return Mage::getResourceModel('realtimedespatch/request_collection')->getProcessableRequestsWithType(
            SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_INVENTORY,
            $this->getBatchLimit()
        );
    }

    /**
     * Checks whether negative inventory quantities are enabled.
     *
     * @return boolean
     */
    public function isNegativeQtyEnabled()
    {
        return Mage::getStoreConfigFlag('realtimedespatch/inventory_import/negative_qtys_enabled');
    }

    /**
     * Checks whether adjustments are to be calculated, and applied.
     *
     * @return boolean
     */
    public function isInventoryAdjustmentEnabled()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/adjust_inventory') !== 0;
    }

    /**
     * Checks whether adjustments are to be calculated for unsent orders.
     *
     * @return boolean
     */
    public function isUnsentOrderAdjustmentEnabled()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/adjust_inventory') > 0;
    }

    /**
     * Checks whether adjustments are to be calculated for active quotes.
     *
     * @return boolean
     */
    public function isActiveQuoteAdjustmentEnabled()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/adjust_inventory') > 1;
    }

    /**
     * Returns the valid unsent order statuses.
     *
     * @return array
     */
    public function getValidUnsentOrderStatuses()
    {
        return explode(',', Mage::getStoreConfig('realtimedespatch/inventory_import/valid_unsent_order_statuses'));
    }

    /**
     * Retrieves the active quote cutoff in days.
     *
     * @return boolean
     */
    public function getActiveQuoteCutoff()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/active_quote_cutoff');
    }

    /**
     * Retrieves the active quote cutoff date.
     *
     * @return boolean
     */
    public function getActiveQuoteCutoffDate()
    {
        return date('Y-m-d H:i:s', strtotime('-'.$this->getActiveQuoteCutoff().' days'));
    }

    /**
     * Retrieves the unsent order cutoff in days.
     *
     * @return boolean
     */
    public function getUnsentOrderCutoff()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/inventory_import/unsent_order_cutoff');
    }

    /**
     * Retrieves the unsent order cutoff date.
     *
     * @return boolean
     */
    public function getUnsentOrderCutoffDate()
    {
        return date('Y-m-d H:i:s', strtotime('-'.$this->getUnsentOrderCutoff().' days'));
    }

    /**
     * Disables the inventory import cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('realtimedespatch/inventory_import/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the inventory import cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'orderflow_inventory_import' AND status = 'pending'");
    }
}