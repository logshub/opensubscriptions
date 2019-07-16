<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

class ScheduledTask extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_scheduled_task';
    protected $_eventPrefix = 'opensubscriptions_scheduled_task';

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\ScheduledTask');
    }
}
