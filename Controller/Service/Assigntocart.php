<?php
namespace Logshub\OpenSubscriptions\Controller\Service;

use \Logshub\OpenSubscriptions\Model\Service;

class Assigntocart extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $serviceFactory;
    protected $customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->serviceFactory = $serviceFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $quoteItemId = $this->getRequest()->getParam('item_id');
        $item = $this->getQuoteItem($quoteItemId);
        $serviceFakeId = $this->getRequest()->getParam('service_id');
        $customerId = $this->customerSession->getCustomerId();
        $result = $this->resultJsonFactory->create();
        if (!$this->getRequest()->isAjax() || !$customerId || !$item) {
            return $result->setData(['status' => -1]);
        }
        $service = $this->serviceFactory->create()->load($serviceFakeId, 'fake_id');
        if ($service->isEmpty()){
            $option = $this->getQuoteItemOption($item, '');
            $option->save();

            return $result->setData(['status' => 1]);
        }

        if ($service->getCustomerId() != $customerId) {
            return $result->setData(['status' => -1]);
        }
        if ($service->getProductId() != $item->getProductId()){
            return $result->setData(['status' => -2]);
        }

        $option = $this->getQuoteItemOption($item, $service->getFakeId());
        $option->save();

        return $result->setData(['status' => 1]);
    }

    /**
     * @var \Magento\Quote\Model\Quote\Item|null
     */
    private function getQuoteItem($itemId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote = $cart->getQuote();

        foreach ($quote->getItems() as $item){
            if ($item->getId() == $itemId){
                return $item;
            }
        }
    }

    /**
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
