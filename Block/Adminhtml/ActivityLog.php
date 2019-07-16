<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml;

class ActivityLog extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'OpenSubscriptions_OpenSubscriptions';
        $this->_controller = 'adminhtml_activityLog';
        $this->_headerText = __('Activity Log');
        parent::_construct();
        $this->removeButton('add');
    }
}
