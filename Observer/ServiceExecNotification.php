<?php
namespace Logshub\OpenSubscriptions\Observer;

class ServiceExecNotification implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->logger = $logger;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $action = $observer->getEvent()->getAction();
        $service = $observer->getEvent()->getService();
        // notification after CREATE only for now
        if ($action !== 'create') {
            return;
        }
        // check service submodule in case of specific needed
        
        try {
            $customer = $service->getCustomer();
            $builder = $this->transportBuilder->setTemplateIdentifier('logshub_opensubscriptions_service_creation')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $this->storeManager->getStore()->getId()])
                ->setFrom('general')
                ->addTo($customer->getEmail(), $customer->getFirstname().' '.$customer->getLastname())
                ->setTemplateVars([
                    'service_fake_id' => $service->getFakeId(),
                    'service_status' => $service->getStatus(),
                    'product_name' => $service->getProduct()->getName(),
                    'url_login' => $this->storeManager->getStore()->getBaseUrl() . '/customer/account/login',
                ]);
            
            // TODO: timeout because it will block everything in case of lag
            
            $transport = $builder->getTransport();
            $transport->getMessage()->setSubject('Service has been created');
            $transport->sendMessage();
            
            $this->logger->info('Notification email after cration has been sent - #' . $service->getServiceId());
            
        } catch (\Exception $ex) {
            $this->logger->error('OPENSUB_SENDERR '. $ex->getMessage());
            return false;
        }
    }
}
