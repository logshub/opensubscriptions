<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Connections;

class Log extends \Magento\Backend\App\Action
{
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
        \OpenSubscriptions\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->coreRegistry = $registry;
        $this->serviceFactory = $serviceFactory;
    }

    /**
     * // TODO: create one class and extend in every service's tab
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
        $html = $layout->createBlock(\OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Connections\Log::class)->toHtml();
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($html);
        return $resultRaw;
    }
}
