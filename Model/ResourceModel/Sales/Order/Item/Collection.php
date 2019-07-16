<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Sales\Order\Item;

class Collection extends \Magento\Sales\Model\ResourceModel\Order\Item\Collection
{
    protected function _beforeLoad()
    {
        parent::_beforeLoad();

        $joinOaOrderItemsTable = $this->getTable('opensubscriptions_services_order_items');
        $this->getSelect()->join(
            ['oaoi' => $joinOaOrderItemsTable],
            'main_table.item_id = oaoi.item_id',
            []
        );
    }

    public function filterByService(int $serviceId)
    {
        $this->addFieldToFilter('oaoi.service_id', $serviceId);
    }

    public function joinOrders(array $fields = [])
    {
        $joinOrdersTable = $this->getTable('sales_order');
        $this->getSelect()->join(
            ['o' => $joinOrdersTable],
            'main_table.order_id = o.entity_id',
            $fields
        );
    }
}
