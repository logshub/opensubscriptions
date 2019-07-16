<?php
namespace Logshub\OpenSubscriptions\Model;

class ActivityLog extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'logshub_opensubscriptions_activity_log';
    protected $_eventPrefix = 'logshub_opensubscriptions_activity_log';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\ResourceModel\ActivityLog');
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }

        return parent::beforeSave();
    }
}
