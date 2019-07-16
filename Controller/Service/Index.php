<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Service;

class Index extends \Magento\Framework\App\Action\Action
{
    const LOGIN_URL = 'customer/account/login';
    
    protected $pageFactory;
    protected $serviceCollectionFactory;
    protected $coreRegistry;
    protected $customerSession;
    /** @var  \Magento\Catalog\Model\ResourceModel\Product\Collection */
    protected $productCollection;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \OpenSubscriptions\OpenSubscriptions\Helper\Data $helperData,
        \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\CollectionFactory $serviceCollectionFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->coreRegistry = $registry;
        $this->customerSession = $customerSession;
        $this->helperData = $helperData;
        $this->serviceCollectionFactory = $serviceCollectionFactory;
        $this->productCollection = $collectionFactory->create();
        return parent::__construct($context);
    }

    // TODO: isAllowed

    public function execute()
    {
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId) {
            return $this->_redirect(self::LOGIN_URL);
        }
        $services = $this->serviceCollectionFactory->create()
            ->filterByCustomer($customerId)
            ->filterByNotDeleted()
            ->orderByDate();
        
        $productsIds = $this->helperData->getClientsProductsIdsConfig();
        if ($productsIds){
            $this->productCollection->addFieldToSelect('*');
            $this->productCollection->addIdFilter([$productsIds]);
            $this->coreRegistry->register('products', $this->productCollection);
        } else {
            $this->coreRegistry->register('products', []);
        }
        
        $this->coreRegistry->register('services', $services);
        $resultPage = $this->pageFactory->create();

        /** @var \Magento\Framework\View\Element\Html\Links $navigationBlock */
        $navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('opensubscriptions/service/index');
        }

        return $resultPage;
    }
}
