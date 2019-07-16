<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml;

class Submodules extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'submodules.phtml';

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context, $data);
    }

    public function getSubmodules()
    {
        return $this->coreRegistry->registry('submodules');
    }

    public function getLayout()
    {
        return $this->layoutFactory->create();
    }
}
