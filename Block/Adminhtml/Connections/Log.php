<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Connections;

class Log extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Logshub_OpenSubscriptions';
        $this->_controller = 'adminhtml_connections_log';
        $this->_headerText = __('Connection Log');
        parent::_construct();
        $this->removeButton('add');
    }
}
