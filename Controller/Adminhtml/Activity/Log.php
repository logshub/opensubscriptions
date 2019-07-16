<?php
namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Activity;

class Log extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
    protected $resultRawFactory;
    protected $coreRegistry;
    protected $serviceFactory;

    /**
     * @see app/code/Magento/Sales/Controller/Adminhtml/Order/CommentsHistory.php
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory
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
        $html = $layout->createBlock(\Logshub\OpenSubscriptions\Block\Adminhtml\ActivityLog::class)->toHtml();
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($html);

        return $resultRaw;
    }
}
