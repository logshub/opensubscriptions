<?php
namespace Logshub\OpenSubscriptions\Block\Checkout\Cart;

use Logshub\OpenSubscriptions\Model\Service;

class AssignService extends \Magento\Checkout\Block\Cart\Additional\Info
{
    protected $_template = 'checkout/cart/assign-service.phtml';
    protected $coreRegistry;
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @todo STATUS ALSO EXPIRED (DISABLED?) - USER WOULD EXTEND EXPIRED SERVICE
     */
    public function getServices()
    {
        $customnerId = $this->customerSession->getCustomerId();
        $product = $this->getItem()->getProduct();
        $submodule = $product->getOpenSubscriptionsSubmodule();
        if (!$customnerId || !$submodule){
            return [];
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $servicesCollection = $objectManager->create('Logshub\OpenSubscriptions\Model\ResourceModel\Service\Collection')
            ->filterByCustomer($customnerId)
            ->filterBySubmodule($submodule)
            ->filterByStatus([Service::ACTIVE, Service::DISABLED])
            ->filterByProduct($product->getId());
        
        return $servicesCollection;
    }

    public function getServiceOptionName(\Logshub\OpenSubscriptions\Model\Service $service)
    {
        if ($service->getName()){
            return $service->getFakeId() . ' (' . $service->getName() . ')';
        }

        return $service->getFakeId() . ' created: ' . substr($service->getCreatedAt(), 0, 10);
    }

    /**
     * @var string|null
     */
    public function getAssignedServiceFakeId()
    {
        $item = $this->getQuoteItem();
        if (!$item){
            return null;
        }

        $currentOption = $item->getOptionByCode(Service::QUOTE_OPTION_NAME);
        if ($currentOption){
            return $currentOption->getValue();
        }
    }

    public function getAssignActionUrl()
    {
        return $this->getUrl('opensubscriptions/service/assigntocart', [
            '_query' => [
                'item_id' => $this->getItem()->getId(),
            ]
        ]);
    }

    /**
     * @var \Magento\Quote\Model\Quote\Item|null
     */
    private function getQuoteItem()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote = $cart->getQuote();

        foreach ($quote->getItems() as $item){
            if ($item->getId() == $this->getItem()->getId()){
                return $item;
            }
        }
    }
}
