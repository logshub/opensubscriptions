<?php

namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Scheduledtasks extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $productCollectionFactory;
    protected $coreRegistry;
    protected $serviceFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \OpenSubscriptions\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->coreRegistry = $registry;
        $this->serviceFactory = $serviceFactory;
    }

    // TODO: BLOCKER _isAllowed

    /**
     * Ajax request for service's tab
     */
    public function execute()
    {
        $serviceId = $this->getRequest()->getParam('service_id');
        $service = $this->serviceFactory->create()->load($serviceId);
        if ($service->isEmpty()) {
            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setContents('Service does not exists');

            return $resultRaw;
        }

        $this->coreRegistry->register('current_service', $service);

        $layout = $this->layoutFactory->create();
        $html = $layout->createBlock(\OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Services\ScheduledTasks::class)->toHtml();
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($html);

        return $resultRaw;
    }
}
