<?php

/**
 * Process Schedule Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Process_Schedule extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/process_schedule', 'entity_id');
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