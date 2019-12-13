<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml;

class Services extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_services';
        $this->_blockGroup = 'Logshub_OpenSubscriptions';
        $this->_headerText = __('Services');
        $this->_addButtonLabel = __('Create New Service');
        parent::_construct();
    }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/create');
    }
}
