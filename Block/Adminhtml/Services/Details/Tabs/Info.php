<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\Details\Tabs;

use OpenSubscriptions\OpenSubscriptions\Model\Service;

class Info extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'services/info.phtml';
    protected $coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getService()
    {
        return $this->coreRegistry->registry('current_service');
    }

    public function getServiceSettings(): \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting\Collection
    {
        return $this->getService()->getSettingsCollection();
    }

    public function getFormAction(string $action): string
    {
        return $this->getUrl('*/*/exec', [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [
                'service_id' => $this->getService()->getServiceId(),
                'act' => $action,
            ]
        ]);
    }
    
    public function getDeleteAction(): string
    {
        return $this->getUrl('*/*/delete', [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => [
                'service_id' => $this->getService()->getServiceId(),
            ]
        ]);
    }

    public function getConnectionUrl(): string
    {
        return $this->getUrl('*/connections/edit', [
            '_query' => [
                'connection_id' => $this->getService()->getConnectionId(),
            ]
        ]);
    }

    public function getProductUrl(): string
    {
        return $this->getUrl('catalog/product/edit', [
            '_query' => [
                'id' => $this->getService()->getProductId(),
            ]
        ]);
    }

    public function getCustomerUrl(): string
    {
        return $this->getUrl('customer/index/edit', [
            '_query' => [
                'id' => $this->getService()->getCustomerId(),
            ]
        ]);
    }

    public function getStatusColor(): string
    {
        switch ($this->getService()->getStatus()) {
            case Service::ACTIVE: return 'green';
            case Service::PENDING: return 'black';
            case Service::DISABLED: return 'red';
            case Service::DELETED: return 'red';
        }

        return 'black';
    }
}
