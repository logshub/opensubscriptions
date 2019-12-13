<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\Connection\Setting;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'setting_id';
    protected $_eventPrefix = 'opensubscriptions_connection_setting_collection';
    protected $_eventObject = 'connection_setting_collection';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\Connection\Setting', 'Logshub\OpenSubscriptions\Model\ResourceModel\Connection\Setting');
    }

    public function addConnectionFiler(int $connectionId)
    {
        $this->getSelect()->where('connection_id = ?', $connectionId);

        return $this;
    }
}
