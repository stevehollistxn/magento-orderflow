<?php

/**
 * Admin Info Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Admin_Info extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether admin info panels are enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('realtimedespatch/admin_info/is_enabled');
    }

    /**
     * Show Exported to Orderflow column on Catalog > Manage Products
     *
     * @return boolean
     */
	public function showExportedColumnOnProductGrid()
	{
		return Mage::getStoreConfigFlag('realtimedespatch/admin_info/product_grid_exported_column');
	}

	/**
	 * Show Exported to Orderflow column on Sales > Orders
	 *
	 * @return boolean
	 */
	public function showExportedColumnOnOrderGrid()
	{
		return Mage::getStoreConfigFlag('realtimedespatch/admin_info/order_grid_exported_column');
	}
}