<?php
namespace Logshub\OpenSubscriptions\Model\Service;

class Setting extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_service_setting';
    protected $_eventPrefix = 'opensubscriptions_service_setting';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\ResourceModel\Service\Setting');
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $this->setUpdatedAt(date('Y-m-d H:i:s'));

        return parent::beforeSave();
    }
}
