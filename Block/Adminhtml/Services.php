<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml;

class Services extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_services';
        $this->_blockGroup = 'OpenSubscriptions_OpenSubscriptions';
        $this->_headerText = __('Services');
        $this->_addButtonLabel = __('Create New Service');
        parent::_construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/create');
    }
}
