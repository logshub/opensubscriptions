<?php
namespace Logshub\OpenSubscriptions\Model;

class ConnectionLog extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_conn_log';
    protected $_eventPrefix = 'opensubscriptions_connection_log';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\ResourceModel\ConnectionLog');
    }
}
