<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\ResourceModel;

class ScheduledTask extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_services_scheduled_tasks', 'id');
    }
}
