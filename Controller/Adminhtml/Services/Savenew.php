<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Savenew extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Escaper $escaper
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->customerFactory = $customerFactory;
        $this->escaper = $escaper;
    }
    
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
        if (!$data['product_id'] || !$data['customer_id']) {
            return $resultRedirect->setPath('*/*/');
        }

        $customer = $this->customerFactory->create()->load((int)$data['customer_id']);
        $product = $this->productFactory->create()->load((int)$data['product_id']);
        if ($customer->isEmpty() || !$product->getOpenSubscriptionsSubmodule()) {
            return $resultRedirect->setPath('*/*/');
        }
        
        $service = $this->_objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\Service');
        $service->addData([
            'product_id' => $product->getId(),
            'connection_id' => $product->getOpenSubscriptionsConnectionId(),
            'customer_id' => $customer->getId(),
            'name' => $this->escaper->escapeHtml($data['name']),
            'submodule' => $product->getOpenSubscriptionsSubmodule(),
            'status' => \OpenSubscriptions\OpenSubscriptions\Model\Service::PENDING,
            'is_created' => 0
        ]);

        try {
            $service->save();
            $service->assignProductSettings($product);
            
            $this->messageManager->addSuccess(__('You saved this service.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving.'));
        }

        return $resultRedirect->setPath('opensubscriptions/services/details', [
            'service_id' => $service->getId(),
            '_current' => true
        ]);
    }
}
