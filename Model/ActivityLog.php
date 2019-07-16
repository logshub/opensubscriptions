<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

class ActivityLog extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_activity_log';
    protected $_eventPrefix = 'opensubscriptions_activity_log';

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\ActivityLog');
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }

        return parent::beforeSave();
    }
}
