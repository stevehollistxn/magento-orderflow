<?php

/**
 * Log Cleaning Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Log_Cleaning extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether log cleaning is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
    	if (!Mage::helper('realtimedespatch')->isEnabled()) {
    		return false;
	    }

	    return (boolean) Mage::getStoreConfig('realtimedespatch/log_cleaning/is_enabled');
    }

    /**
     * Web Log Duration Getter.
     *
     * @return integer
     */
    public function getWebLogDuration()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/log_cleaning/web_log_duration');
    }

    /**
     * XML Log Duration Getter.
     *
     * @return integer
     */
    public function getXmlLogDuration()
    {
        return (integer) Mage::getStoreConfig('realtimedespatch/log_cleaning/xml_log_duration');
    }
}