<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml;

class Connections extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_connections';
        $this->_blockGroup = 'OpenSubscriptions_OpenSubscriptions';
        $this->_headerText = __('Connections');
        $this->_addButtonLabel = __('Create New Connection');
        parent::_construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/create');
    }
}
