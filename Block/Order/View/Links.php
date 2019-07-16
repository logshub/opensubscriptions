<?php
namespace Logshub\OpenSubscriptions\Block\Order\View;

class Links extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'order/view/links.phtml';
    protected $coreRegistry;
    protected $serviceResourceModel;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Logshub\OpenSubscriptions\Model\ResourceModel\Service $serviceResourceModel,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->serviceResourceModel = $serviceResourceModel;
        parent::__construct($context, $data);
    }

    public function getServices()
    {
        if ($order = $this->getOrder()) {
            return $this->serviceResourceModel->getOrderServices($order->getId());
        }
        return [];
    }
    
    public function getViewUrl(\Logshub\OpenSubscriptions\Model\Service $service)
    {
        return $this->getUrl('opensubscriptions/service/details', [
            '_query' => [
                'service_id' => $service->getFakeId(),
            ]
        ]);
    }
    
    /**
     * Retrieve current order model instance. The same way as other blocks
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }
}
