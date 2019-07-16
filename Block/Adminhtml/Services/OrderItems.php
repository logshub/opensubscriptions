<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services;

class OrderItems extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'OpenSubscriptions_OpenSubscriptions';
        $this->_controller = 'adminhtml_services_orderItems';
        $this->_headerText = __('Order Items');
        parent::_construct();
        $this->removeButton('add');
    }
}
