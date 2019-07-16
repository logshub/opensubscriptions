<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel;

class ActivityLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_activity_logs', 'log_id');
    }
}
