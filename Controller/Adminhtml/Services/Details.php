<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Details extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $coreRegistry;
    protected $serviceFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \OpenSubscriptions\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->serviceFactory = $serviceFactory;
    }

    // TODO: _isAllowed

    public function execute()
    {
        $serviceId = $this->getRequest()->getParam('service_id');
        $service = $this->serviceFactory->create()->load($serviceId);

        if ($service->isEmpty()) {
            $this->messageManager->addError(__('Service does not exists'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $this->coreRegistry->register('current_service', $service);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('OpenSubscriptions_OpenSubscriptions::index');
        $resultPage->getConfig()->getTitle()->prepend((__('Service Details')));

        return $resultPage;
    }
}
