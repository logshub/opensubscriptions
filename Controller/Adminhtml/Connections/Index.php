<?php

namespace Logshub\OpenSubscriptions\Controller\Adminhtml\Connections;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logshub_OpenSubscriptions::index');
        $resultPage->getConfig()->getTitle()->prepend((__('Connections')));

        return $resultPage;
    }
}
