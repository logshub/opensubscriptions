<?php

namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $serviceFactory;
    protected $activityLogFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \OpenSubscriptions\OpenSubscriptions\Model\ServiceFactory $serviceFactory,
        \OpenSubscriptions\OpenSubscriptions\Model\ActivityLogFactory $activityLogFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->serviceFactory = $serviceFactory;
        $this->activityLogFactory = $activityLogFactory;
    }

    // TODO: _isAllowed

    public function execute()
    {
        $serviceId = $this->getRequest()->getParam('service_id');
        $service = $this->serviceFactory->create()->load($serviceId);
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        if ($service->isEmpty()) {
            return $result->setData([
                'error' => 'Service not found'
            ]);
        }

        if ($service->getIsCreated()) {
            return $result->setData([
                'error' => 'Unable to permanently delete service. It is already created'
            ]);
        }

        try {
            $service->delete();
            return $result->setData([
                'success' => true
            ]);
        } catch (\OpenSubscriptions\OpenSubscriptions\Exception\CommandException $e) {
            return $result->setData([
                'error' => $e->getMessage()
            ]);
        }
    }
}
