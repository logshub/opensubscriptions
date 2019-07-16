<?php
namespace Logshub\OpenSubscriptions\Block\Service\Details;

use \Magento\Framework\View\Element\Template;

class Tabs extends Template
{
    protected $_template = 'services/details/tabs.phtml';
    /**
     * Tabs structure
     *
     * @var array
     */
    protected $tabs = [];
    protected $activeTabId = 'general';

    protected $coreRegistry;
    protected $eventManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->eventManager = $eventManager;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->addTab('back', [
            'label' => __('Back to services list'),
            'url' => $this->getUrl('opensubscriptions/service/index')
        ]);
        $this->addTab('general', [
            'label' => __('General informations'),
            'url' => $this->getUrl('opensubscriptions/service/details', [
                'service_id' => $this->getService()->getFakeId(),
            ])
        ]);

        $this->eventManager->dispatch('opensubscriptions_frontend_service_tabs_' . $this->getService()->getSubmodule(), [
            'tabs' => $this,
            'service' => $this->getService(),
        ]);

        parent::_construct();
    }

    public function getService()
    {
        // TODO: make abstract block and place it there?
        $service = $this->coreRegistry->registry('current_service');
        if (!$service) {
            throw new \Exception('No service found in registry');
        }
        
        return $service;
    }

    public function getTabs()
    {
        return $this->tabs;
    }
    
    public function getActiveTabId()
    {
        if ($this->coreRegistry->registry('current_tab_id')) {
            return $this->coreRegistry->registry('current_tab_id');
        }
        
        return $this->activeTabId;
    }

    /**
     * Add new tab
     *
     * @param   string $tabId
     * @param   array $tab
     * @return  $this
     * @throws  \Exception
     */
    public function addTab($tabId, array $tab)
    {
        if (empty($tabId)) {
            throw new \Exception(__('Please correct the tab configuration and try again. Tab Id should be not empty'));
        }
        $this->tabs[$tabId] = new \Magento\Framework\DataObject($tab);

        if (!$this->tabs[$tabId]->getUrl() || !$this->tabs[$tabId]->getLabel()) {
            throw new \Exception(__('Url and label are required fields for tab'));
        }

        $this->tabs[$tabId]->setId($tabId);
        $this->tabs[$tabId]->setTabId($tabId);

        return $this;
    }
}
