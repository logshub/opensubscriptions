<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Services\OrderItems;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('opensubscriptions_orderitems_grid');
        $this->setDefaultSort('item_id');
        $this->setUseAjax(true);
    }

    public function getService()
    {
        return $this->coreRegistry->registry('current_service');
    }

    protected function _prepareCollection()
    {
        // TODO: MINOR better way ?
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->create('Logshub\OpenSubscriptions\Model\ResourceModel\Sales\Order\Item\Collection');
        $collection->removeAllFieldsFromSelect();
        $collection->addFieldToSelect(['item_id', 'name', 'base_price', 'qty_ordered']);
        $collection->joinOrders(['increment_id', 'status', 'customer_id', 'base_grand_total', 'created_at', 'total_qty_ordered']);
        $collection->filterByService($this->getService()->getServiceId());

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('item_id', [
            'header' => __('Item Id'),
            'sortable' => true,
            'index' => 'item_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        $this->addColumn('created_at', [
            'header' => __('Created At'),
            'sortable' => true,
            'index' => 'created_at',
        ]);
        $this->addColumn('customer_id', [
            'header' => __('Customer Id'),
            'index' => 'customer_id',
        ]);
        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
        ]);
        // TODO: MINOR help for this column, that could be multiplied by qty
        $this->addColumn('base_price', [
            'header' => __('Item Base Price'),
            'index' => 'base_price',
        ]);
        $this->addColumn('qty_ordered', [
            'header' => __('Item Qty Ordered'),
            'index' => 'qty_ordered',
        ]);
        $this->addColumn('increment_id', [
            'header' => __('Order Increment Id'),
            'index' => 'increment_id',
        ]);
        $this->addColumn('status', [
            'header' => __('Order Status'),
            'index' => 'status',
        ]);

        $this->addColumn('base_grand_total', [
            'header' => __('Order Base Grand Total'),
            'index' => 'base_grand_total',
        ]);
        $this->addColumn('total_qty_ordered', [
            'header' => __('Order Total Qty Ordered'),
            'index' => 'total_qty_ordered',
        ]);

        return parent::_prepareColumns();
    }
}
