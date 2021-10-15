<?php

/**
 * Request Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Request extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/request', 'entity_id');
    }

	/**
	 * {@inheritdoc}
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		if (!$object->getId()) {
			$object->setCreated(Mage::getSingleton('core/date')->gmtDate());
		}
		return $this;
	}
}