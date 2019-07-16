<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'connection_id';
    protected $_eventPrefix = 'opensubscriptions_connection_collection';
    protected $_eventObject = 'connection_collection';

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\Connection', 'OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection');
    }
}
