<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Save extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OpenSubscriptions_OpenSubscriptions::services_save');
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
        
        $model = $this->_objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\Service');
        $id = $this->getRequest()->getParam('service_id');
        if ($id) {
            $model->load($id);
        }
        $model->setData($data);

        try {
            $model->save();
            
            $this->messageManager->addSuccess(__('You saved this service.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving.'));
        }

        return $resultRedirect->setPath('opensubscriptions/services/details', ['service_id' => $model->getId(), '_current' => true]);
    }
}
