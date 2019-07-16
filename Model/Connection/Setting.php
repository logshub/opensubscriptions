<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Connection;

class Setting extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_connection_setting';
    protected $_eventPrefix = 'opensubscriptions_connection_setting';

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection\Setting');
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $this->setUpdatedAt(date('Y-m-d H:i:s'));

        return parent::beforeSave();
    }
    
    public function saveSetting($connectionId, $name, $value)
    {
        return $this->_getResource()->saveSetting($connectionId, $name, $value);
    }
}
