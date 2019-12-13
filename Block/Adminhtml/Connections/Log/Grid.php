<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Connections\Log;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $connectionLogFactory;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Logshub\OpenSubscriptions\Model\ConnectionLogFactory $connectionLogFactory,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->connectionLogFactory = $connectionLogFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('opensubscriptions_connections_log_grid');
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
        $collection = $this->connectionLogFactory->create()->getCollection();
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
        $this->addColumn('admin_email', [
            'header' => __('Admin Email'),
            'index' => 'admin_email',
        ]);
        $this->addColumn('service_id', [
            'header' => __('Service ID'),
            'index' => 'service_id',
        ]);
        $this->addColumn('connection_id', [
            'header' => __('Connection ID'),
            'index' => 'connection_id',
        ]);
        $this->addColumn('action', [
            'header' => __('Action'),
            'index' => 'action',
        ]);
        $this->addColumn('request', [
            'header' => __('Request'),
            'index' => 'request',
        ]);
        $this->addColumn('response', [
            'header' => __('Response'),
            'index' => 'response',
        ]);

        return parent::_prepareColumns();
    }
}
