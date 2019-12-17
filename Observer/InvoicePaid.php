<?php
namespace Logshub\OpenSubscriptions\Observer;

use Logshub\OpenSubscriptions\Model\Service;

/**
 * Order Item with selected service -> update expiry date
 * Order Item without selected service -> create a new service with calculated expiry date
 */
class InvoicePaid implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getInvoice()->getOrder();
        foreach ($order->getAllItems() as $item) {
            $product = $item->getProduct();
            if (!$product->getOpenSubscriptionsSubmodule()) {
                continue;
            }
            // TODO: posssible bug: an order without quote will not have service assigned. Is it possible?
            $assignedServiceId = $this->getAssignedServiceId($item);
            if ($assignedServiceId){ // selected servicve on cart
                $service = $this->getNewServiceInstance();
                $service->loadByFakeId($assignedServiceId);
                $expireAt = $this->getExpireAt((int)$item->getQtyOrdered(), $service->getExpireAt());
                $service->setExpireAt($expireAt);
                $service->save();

                $this->logger->info('OA-INV-PAID Saving expire_at: Q ' . $item->getQuoteItemId() . ' S ' . $service->getId() . ' EX ' . $expireAt);

            } else { // no selected service -> create a new one
                $service = $this->createNewService($order, $item, $product);

                $this->logger->info('OA-INV-PAID Saving new service: Q ' . $item->getQuoteItemId() . ' S ' . $service->getId());
            }

            // saving into opensubscriptions_services_order_items
            $service->assignOrderItem($item);
        }
    }

    private function createNewService(
        \Magento\Sales\Model\Order $order,
        \Magento\Sales\Model\Order\Item $orderItem,
        \Magento\Catalog\Model\Product $product
    ) {
        $service = $this->getNewServiceInstance();
        $service->addData([
            'product_id' => $orderItem->getProductId(),
            'connection_id' => $product->getOpenSubscriptionsConnectionId(),
            'customer_id' => $order->getCustomerId(), // TODO: not workign while guest
            'submodule' => $product->getOpenSubscriptionsSubmodule(),
            'status' => Service::PENDING,
            'is_created' => 0,
            'expire_at' => $this->getExpireAt((int)$orderItem->getQtyOrdered()),
        ]);
        $service->save();
        $service->assignOrderItem($orderItem);
        $service->assignProductSettings($product);

        return $service;
    }

    private function getNewServiceInstance()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        return $objectManager->create('Logshub\OpenSubscriptions\Model\Service');
    }

    /**
     * @param $qty int number of months
     * @param $currentExpireAt string
     */
    private function getExpireAt($qty, $currentExpireAt = null)
    {
        $baseDate = !empty($currentExpireAt) ? new \DateTime($currentExpireAt) : new \DateTime();
        $interval = new \DateInterval('P'.$qty.'M');

        return $baseDate->add($interval)->format('Y-m-d H:i:s');
    }

    /**
     * 
     * @return string Fake id of service
     */
    private function getAssignedServiceId(\Magento\Sales\Model\Order\Item $orderItem)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $quoteItemOption = $objectManager->create('Magento\Quote\Model\ResourceModel\Quote\Item\Option\Collection')
            ->addFieldToFilter('item_id', $orderItem->getQuoteItemId())
            ->addFieldToFilter('code', Service::QUOTE_OPTION_NAME)
            ->getFirstItem();

        return $quoteItemOption->getValue();
    }
}
