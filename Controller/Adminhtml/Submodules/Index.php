<?php
namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Submodules;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $coreRegistry;
    protected $serviceFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Logshub\OpenSubscriptions\Model\ServiceFactory $serviceFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->serviceFactory = $serviceFactory;
    }

    // TODO: _isAllowed

    public function execute()
    {
        $submodules = \Logshub\OpenSubscriptions\Model\Submodule::all();
        $this->coreRegistry->register('submodules', $submodules);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logshub_OpenSubscriptions::index');
        $resultPage->getConfig()->getTitle()->prepend((__('Submodules')));

        return $resultPage;
    }
}
