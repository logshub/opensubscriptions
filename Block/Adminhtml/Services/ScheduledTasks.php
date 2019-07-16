<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services;

class ScheduledTasks extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'OpenSubscriptions_OpenSubscriptions';
        $this->_controller = 'adminhtml_services_scheduledTasks';
        $this->_headerText = __('Scheduled tasks');
        parent::_construct();
        $this->removeButton('add');
    }
}
