<?php
namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Connections;

use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('connection_id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Logshub\OpenSubscriptions\Model\Connection');
                $model->load($id);
                $model->delete();
                $this->_redirect('*/*/');
                $this->messageManager->addSuccess(__('Delete successfull.'));
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*/edit', ['id' => $id]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        $this->_redirect('*/*/');
    }
}
