<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'setting_id';
    protected $_eventPrefix = 'opensubscriptions_service_setting_collection';
    protected $_eventObject = 'service_setting_collection';

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\Service\Setting', 'OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting');
    }

    public function addServiceFiler(int $serviceId)
    {
        $this->getSelect()->where('service_id = ?', $serviceId);

        return $this;
    }
}
