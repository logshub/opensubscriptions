<?php

namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Services;

class Exec extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $serviceFactory;
    protected $activityLogFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory,
        \Logshub\OpenSubscriptions\Model\ActivityLogFactory $activityLogFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->eventManager = $eventManager;
        $this->serviceFactory = $serviceFactory;
        $this->activityLogFactory = $activityLogFactory;
    }

    // TODO: _isAllowed

    public function execute()
    {
        $serviceId = $this->getRequest()->getParam('service_id');
        $action = $this->getRequest()->getParam('act');
        $service = $this->serviceFactory->create()->load($serviceId);

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        if ($service->isEmpty()) {
            return $result->setData([
                'error' => 'Service not found'
            ]);
        }

        $activityLog = $this->activityLogFactory->create();
        $activityLog->addData([
            'service_id' => $serviceId,
            'creator_admin_id' => null, // TODO: from session
            'submodule' => $service->getSubmodule(),
            'action' => $action,
        ]);
        try {
            $command = $service->getSubmoduleCommand($action);
            if (!$command->isExecutable($service)) {
                return $result->setData([
                    'error' => 'Command is not executable in current state'
                ]);
            }
            $activityLog->save();
            $commandResult = $command->exec($service);

            $activityLog->setSuccess((int) $commandResult);
            $activityLog->save();
            
            // make sure to check submodule in listener
            $this->eventManager->dispatch('opensubscriptions_service_exec_after', [
                'action' => $action,
                'service' => $service,
            ]);

            return $result->setData([
                'success' => $commandResult
            ]);
        } catch (\Logshub\OpenSubscriptions\Exception\CommandException $e) {
            $activityLog->setSuccess(false);
            $activityLog->setMessage($e->getMessage());
            $activityLog->save();

            return $result->setData([
                'error' => $e->getMessage()
            ]);
        }
    }
}
