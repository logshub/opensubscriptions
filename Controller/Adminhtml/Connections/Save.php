<?php
namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Connections;

class Save extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Logshub_OpenSubscriptions::save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }
        
        $model = $this->_objectManager->create('Logshub\OpenSubscriptions\Model\Connection');
        $id = $this->getRequest()->getParam('connection_id');
        if ($id) {
            $model->load($id);
        }

        $model->setData($data);

        $this->_eventManager->dispatch('opensubscriptions_connection_prepare_save', [
            'connection' => $model,
            'request' => $this->getRequest()
        ]);

        try {
            // saving main object
            $model->save();
            // save additional settings
            $model->saveSubmoduleSettings();
            
            $this->messageManager->addSuccess(__('You saved this Connection.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['connection_id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving.'));
        }

        $this->_getSession()->setFormData($data);
        
        return $resultRedirect->setPath('*/*/edit', ['connection_id' => $this->getRequest()->getParam('connection_id')]);
    }
}
