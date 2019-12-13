<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Submodules;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected $coreRegistry;
    protected $eventManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->eventManager = $eventManager;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('submodules_tabs');
        $this->setDestElementId('sales_order_view');
        $this->setTitle(__('Submodules'));
    }

    public function getSubmodules()
    {
        return $this->coreRegistry->registry('submodules');
    }

    protected function _prepareLayout()
    {
        foreach ($this->getSubmodules() as $submodule){
            $helpBlock = $submodule->getHelpBlock();
            if ($helpBlock){
                $this->addTab($submodule->getId(), [
                    'label' => $submodule->getId(),
                    'content' => $this->getLayout()->createBlock($helpBlock)->toHtml(),
                ]);
            }
        }

        $this->addTab('docs', [
            'label' => __('Documentation'),
            'content' => 'TODO...',
        ]);

        $this->addTab('how-to', [
            'label' => __('How to create a new submodule?'),
            'content' => 'TODO...',
        ]);

        return parent::_prepareLayout();
    }
}
