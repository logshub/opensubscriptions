<?php
namespace Logshub\OpenSubscriptions\Controller\Service;

class Details extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;
    protected $serviceFactory;
    protected $coreRegistry;
    protected $customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->coreRegistry = $registry;
        $this->customerSession = $customerSession;
        $this->serviceFactory = $serviceFactory;
        return parent::__construct($context);
    }

    // TODO: isAllowed

    public function execute()
    {
        $serviceFakeId = $this->getRequest()->getParam('service_id');
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId) {
            return $this->_redirect(Index::LOGIN_URL);
        }
        $service = $this->serviceFactory->create()
            ->load($serviceFakeId, 'fake_id');

        if ($service->isEmpty() || $service->getCustomerId() != $customerId) {
            return $this->_redirect(Index::LOGIN_URL);
        }

        $this->coreRegistry->register('current_service', $service);
        $resultPage = $this->pageFactory->create();

        return $resultPage;
    }
}
