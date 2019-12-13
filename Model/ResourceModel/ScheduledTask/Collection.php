<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\ScheduledTask;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'opensubscriptions_scheduledtask_collection';
    protected $_eventObject = 'scheduledtask_collection';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\ScheduledTask', 'Logshub\OpenSubscriptions\Model\ResourceModel\ScheduledTask');
    }

    public function joinServices(array $fields = [])
    {
        $joinServicesTable = $this->getTable('opensubscriptions_services');

        $this->getSelect()->joinLeft(['srv' => $joinServicesTable], 'main_table.service_id = srv.service_id', $fields);
    }

    public function filterByService(int $serviceId)
    {
        $this->addFieldToFilter('service_id', $serviceId);
    }
}
