<?php
namespace Logshub\OpenSubscriptions\Block\Customer\Account;

use Magento\Customer\Block\Account\SortLinkInterface;

class ServiceLink extends \Magento\Framework\View\Element\Html\Link\Current implements SortLinkInterface
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Magento\Customer\Model\Session $customerSession,
        \Logshub\OpenSubscriptions\Model\ResourceModel\Service $serviceResourceModel,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        
        $customerId = $customerSession->getCustomerId();
        $total = $customerId ? $serviceResourceModel->countCustomerServices($customerId) : 0;

        $this->setData('path', 'opensubscriptions/service/index');
        $this->setData('label', 'My Services ('.$total.')');
        $this->setData('sortOrder', 999);
        $this->setTemplate(false);
    }

    public function getAttributes()
    {
        return [
            'style' => 'background-color: #ededed;'
            // border-right: 3px solid #ff5501; border-left: 3px solid #ff5501;
        ];
    }

    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}