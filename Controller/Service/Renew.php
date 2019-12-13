<?php
namespace Logshub\OpenSubscriptions\Controller\Service;

use \Logshub\OpenSubscriptions\Model\Service;

class Renew extends \Magento\Framework\App\Action\Action
{
    protected $serviceFactory;
    protected $customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->productFactory = $productFactory;
        $this->customerSession = $customerSession;
        $this->serviceFactory = $serviceFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $serviceFakeId = $this->getRequest()->getParam('service_id');
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId) {
            return $this->_redirect(Index::LOGIN_URL);
        }
        $service = $this->serviceFactory->create()
            ->load($serviceFakeId, 'fake_id');

        if ($service->isEmpty() || $service->getCustomerId() != $customerId || !$service->canRenew()) {
            return $this->_redirect(Index::LOGIN_URL);
        }

        $store = $this->storeManager->getStore();
        $customer = $this->customerRepository->getById($customerId);
        $product = $this->productFactory->create()->load($service->getProductId());

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        foreach ($cart->getQuote()->getAllItems() as $item){
            $cart->removeItem($item->getId());
        }
        $quoteItem = $cart->addProduct($product, 12);

        $cart->save();
        $quoteItem = $cart->getQuote()->getAllItems()[0];

        // assign service
        $option = $this->getQuoteItemOption($quoteItem, $service->getFakeId());
        $option->save();

        return $this->_redirect('checkout/cart');
    }

    /**
     * @todo the same as Assigntocart controller - extend?
     * @var \Magento\Quote\Model\Quote\Item\Option
     */
    private function getQuoteItemOption(\Magento\Quote\Model\Quote\Item $item, $serviceFakeId)
    {
        $value = $serviceFakeId;
        $currentOption = $item->getOptionByCode(Service::QUOTE_OPTION_NAME);
        if ($currentOption){
            $currentOption->setValue($value);

            return $currentOption;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $option = $objectManager->get('\Magento\Quote\Model\Quote\Item\Option');
        $option->addData([
            'code' => Service::QUOTE_OPTION_NAME,
            'value' => $value,
        ]);
        $option->setItem($item);
        $option->setProduct($item->getProduct());

        return $option;
    }
}
