<?php
namespace Logshub\OpenSubscriptions\Block\Service;

class Details extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'services/details.phtml';
    protected $coreRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Service Details'));
    }

    public function getService()
    {
        return $this->coreRegistry->registry('current_service');
    }
}
