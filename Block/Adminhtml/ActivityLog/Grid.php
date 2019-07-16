<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\ActivityLog;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $activityLogFactory;
    protected $coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \OpenSubscriptions\OpenSubscriptions\Model\ActivityLogFactory $activityLogFactory,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->activityLogFactory = $activityLogFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('opensubscriptions_activity_log_grid');
        $this->setDefaultSort('log_id');
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
        $collection = $this->activityLogFactory->create()->getCollection();
        $collection->joinAdmins(['admin_email' => 'email']);
        if ($this->getService()) {
            $collection->filterByService($this->getService()->getServiceId());
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('log_id', [
            'header' => __('Log Id'),
            'sortable' => true,
            'index' => 'log_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        $this->addColumn('created_at', [
            'header' => __('Created At'),
            'sortable' => true,
            'index' => 'created_at',
        ]);
        $this->addColumn('service_id', [
            'header' => __('Service ID'),
            'index' => 'service_id',
        ]);
        $this->addColumn('success', [
            'header' => __('Success'),
            'index' => 'success',
        ]);
        $this->addColumn('action', [
            'header' => __('Action'),
            'index' => 'action',
        ]);
        $this->addColumn('admin_email', [
            'header' => __('Admin Email'),
            'index' => 'admin_email',
        ]);
        $this->addColumn('submodule', [
            'header' => __('Submodule'),
            'index' => 'submodule',
        ]);
        $this->addColumn('message', [
            'header' => __('Message'),
            'index' => 'message',
        ]);

        return parent::_prepareColumns();
    }
}
