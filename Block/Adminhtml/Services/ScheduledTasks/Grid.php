<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\ScheduledTasks;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $scheduledTaskFactory;
    protected $coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \OpenSubscriptions\OpenSubscriptions\Model\ScheduledTaskFactory $scheduledTaskFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->scheduledTaskFactory = $scheduledTaskFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('opensubscriptions_scheduled_tasks_grid');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
    }

    public function getService()
    {
        return $this->coreRegistry->registry('current_service');
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        // TODO: filetr by ID
        $collection = $this->scheduledTaskFactory->create()->getCollection();
        $collection->filterByService($this->getService()->getServiceId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', [
            'header' => __('Id'),
            'sortable' => true,
            'index' => 'id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        $this->addColumn('scheduled_date', [
            'header' => __('Scheduled At'),
            'sortable' => true,
            'index' => 'scheduled_date',
        ]);
        $this->addColumn('command', [
            'header' => __('Command'),
            'index' => 'command',
        ]);
        $this->addColumn('done', [
            'header' => __('Done'),
            'index' => 'done',
        ]);

        return parent::_prepareColumns();
    }
}
