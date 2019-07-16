<?php
namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Connections;

class Create extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Logshub_OpenSubscriptions::save');
    }

    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        // TODO: forward instead of redirect ??
        return $this->_redirect('*/*/edit');
    }
}
