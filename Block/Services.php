<?php
namespace OpenSubscriptions\OpenSubscriptions\Block;

class Services extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'services/list.phtml';
    protected $coreRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Services'));
    }

    public function getServices()
    {
        return $this->coreRegistry->registry('services');
    }
    
    public function getProducts()
    {
        return $this->coreRegistry->registry('products');
    }

    public function getViewUrl(\OpenSubscriptions\OpenSubscriptions\Model\Service $service)
    {
        return $this->getUrl('*/*/details', [
            '_query' => [
                'service_id' => $service->getFakeId(),
            ]
        ]);
    }
}
