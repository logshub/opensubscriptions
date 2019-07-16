<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\Details;

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
        $this->setId('services_details_tabs');
        $this->setDestElementId('sales_order_view');
        $this->setTitle(__('Service'));
    }

    public function getService()
    {
        return $this->coreRegistry->registry('current_service');
    }

    protected function _prepareLayout()
    {
        $this->addTab('info', [
            'label' => __('Informations'),
            'content' => $this->getLayout()->createBlock('OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\Details\Tabs\Info')->toHtml(),
            'class' => 'ajax'
        ]);

        $this->addTab('edit', [
            'label' => __('Edit Service'),
            'content' => $this->getLayout()->createBlock('OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\Details\Tabs\Edit')->toHtml(),
        ]);

        $this->eventManager->dispatch('opensubscriptions_adminhtml_service_tabs_' . $this->getService()->getSubmodule(), [
            'tabs' => $this,
            'service' => $this->getService(),
        ]);

        $this->addTab('order_items', [
            'label' => __('Order Items'),
            'url' => $this->getUrl('opensubscriptions/services/orderitems', [
                '_current' => true,
                'service_id' => $this->getService()->getServiceId(),
            ]),
            'class' => 'ajax'
        ]);

        // TODO: MINOR invoices tab? how about multi items orders?

        $this->addTab('scheduled_tasks', [
            'label' => __('Scheduled Tasks'),
            'url' => $this->getUrl('opensubscriptions/services/scheduledtasks', [
                '_current' => true,
                'service_id' => $this->getService()->getServiceId(),
            ]),
            'class' => 'ajax'
        ]);
        $this->addTab('activity_log', [
            'label' => __('Activity Log'),
            'url' => $this->getUrl('opensubscriptions/activity/log', [
                '_current' => true,
                'service_id' => $this->getService()->getServiceId(),
            ]),
            'class' => 'ajax'
        ]);
        $this->addTab('connection_log', [
            'label' => __('Connection Log'),
            'url' => $this->getUrl('opensubscriptions/connections/log', [
                '_current' => true,
                'service_id' => $this->getService()->getServiceId(),
            ]),
            'class' => 'ajax'
        ]);

        return parent::_prepareLayout();
    }
}
