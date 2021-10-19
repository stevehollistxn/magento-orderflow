<?php

/**
 * RTD Data Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the module is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('realtimedespatch/general/is_enabled');
    }

	/**
	 * Checks whether the module is configured
	 */
	public function isConfigured()
	{
		return $this->getApiEndpoint() && $this->getApiUsername() && $this->getApiPassword() &&
			$this->getApiChannel() && $this->getApiOrganisation();
	}

    /**
     * Returns the Admin Contact Name.
     *
     * @return string
     */
    public function getAdminName()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/admin_name');
    }

    /**
     * Returns the Admin Contact Email.
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/admin_email');
    }

	/**
	 * Returns the Orderflow timezone.
	 *
	 * @return string
	 */
	public function getTimezone()
	{
		return (string) Mage::getStoreConfig('realtimedespatch/general/timezone');
	}

    /**
     * Returns the API Endpoint.
     *
     * @return string
     */
    public function getApiEndpoint()
    {
        $endpoint = (string) Mage::getStoreConfig('realtimedespatch/general/endpoint');
        if (!empty($endpoint)) {
        	$endpoint = rtrim($endpoint, '/') . '/';
        }

        return $endpoint;
    }

    /**
     * Returns the API Username.
     *
     * @return string
     */
    public function getApiUsername()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/username');
    }

    /**
     * Returns the API Password.
     *
     * @return string
     */
    public function getApiPassword()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/password');
    }

    /**
     * Returns the API Organisation.
     *
     * @return string
     */
    public function getApiOrganisation()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/organisation');
    }

    /**
     * Returns the API Channel.
     *
     * @return string
     */
    public function getApiChannel()
    {
        return (string) Mage::getStoreConfig('realtimedespatch/general/channel');
    }
}