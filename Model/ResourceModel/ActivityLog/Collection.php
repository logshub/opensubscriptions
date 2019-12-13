<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\ActivityLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'log_id';
    protected $_eventPrefix = 'opensubscriptions_activitylog_collection';
    protected $_eventObject = 'activitylog_collection';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\ActivityLog', 'Logshub\OpenSubscriptions\Model\ResourceModel\ActivityLog');
    }

    public function joinAdmins(array $fields = [])
    {
        $joinAdminsTable = $this->getTable('admin_user');

        $this->getSelect()->joinLeft(['adm' => $joinAdminsTable], 'main_table.creator_admin_id = adm.user_id', $fields);
    }

    public function joinServices(array $fields = [])
    {
        $joinServicesTable = $this->getTable('opensubscriptions_services');

        $this->getSelect()->joinLeft(['srv' => $joinServicesTable], 'main_table.service_id = srv.service_id', $fields);
    }

    public function filterByService(int $serviceId)
    {
        $this->addFieldToFilter('main_table.service_id', $serviceId);
    }
}
