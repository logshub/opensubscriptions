<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel;

class Connection extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_connections', 'connection_id');
    }
}
