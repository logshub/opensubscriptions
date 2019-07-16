<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel;

class ConnectionLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_connection_logs', 'log_id');
    }
}
