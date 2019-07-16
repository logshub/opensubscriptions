<?php
namespace OpenSubscriptions\OpenSubscriptions\Controller\Adminhtml\Services;

class Create extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \OpenSubscriptions\OpenSubscriptions\Helper\Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->oaHelper = $helper;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OpenSubscriptions_OpenSubscriptions::services_new');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('OpenSubscriptions_OpenSubscriptions::services');
        return $resultPage;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(__('New Service'), __('New Service'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Service'));

        $this->coreRegistry->register('form_default', $this->oaHelper->getServiceCreationConfig());

        return $resultPage;
    }
}
