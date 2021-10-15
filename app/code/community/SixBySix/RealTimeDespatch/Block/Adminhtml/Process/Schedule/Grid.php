<?php

/**
 * Process Schedule Grid Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Process_Schedule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Default sort parameter.
     *
     * @var string
     */
    protected $_defaultSort = 'scheduled';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setSaveParametersInSession(true);
        $this->setId('entity_id');
        $this->setIdFieldName('entity_id');
        $this->setDefaultSort('scheduled', 'desc');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('realtimedespatch/process_schedule')
            ->getCollection();

        $collection->getSelect()->group('executed')->group('entity');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity', array(
            'header'   => Mage::helper('realtimedespatch')->__('Process'),
            'align'    => 'left',
            'index'    => 'entity',
            'renderer' => 'realtimedespatch/adminhtml_renderer_process_name',
            'type'     => 'options',
            'options'  => array(
                'Product'   => 'Product Export',
                'Order'     => 'Order Export',
                'Shipment'  => 'Shipment Import',
                'Inventory' => 'Inventory Import',
            )
        ));

        $this->addColumn('message_id', array(
            'header'   => Mage::helper('realtimedespatch')->__('Message ID'),
            'align'    =>'left',
            'index'    => 'message_id',
            'renderer' => 'realtimedespatch/adminhtml_renderer_process_message_ID',
        ));

        $this->addColumn('scheduled', array(
            'header' => Mage::helper('realtimedespatch')->__('Scheduled'),
            'align'  =>'left',
            'index'  => 'scheduled',
	        'type'   => 'datetime',
	        'width'  => '150px'
        ));

        $this->addColumn('executed', array(
            'header'   => Mage::helper('realtimedespatch')->__('Executed'),
            'align'    =>'left',
            'index'    => 'executed',
	        'type'   => 'datetime',
	        'width'  => '150px',
            'renderer' => 'realtimedespatch/adminhtml_renderer_process_executed',
        ));

        $this->addColumn('status', array(
            'header'   => Mage::helper('realtimedespatch')->__('Status'),
            'align'    =>'left',
            'index'    => 'status',
            'renderer' => 'realtimedespatch/adminhtml_renderer_process_status',
        ));

	    $this->addColumn('Previous exchange', array(
		    'header'   => Mage::helper('realtimedespatch')->__('Previous exchange'),
		    'align'    =>'left',
		    'index'    => 'last_executed_with_items',
		    'type'     => 'datetime',
		    'width'    => '150px',
		    'renderer' => 'realtimedespatch/adminhtml_renderer_process_executed_with_items',
	    ));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        if ( ! $row->getParentId()) {
            return '';
        }

        if ($row->getType() == 'export') {
            return $this->getUrl('*/'.$row->getEntity().'Exports/view', array('id' => $row->getParentId()));
        }

        return $this->getUrl('*/'.$row->getEntity().'Imports/view', array('id' => $row->getParentId()));
    }
}