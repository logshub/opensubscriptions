<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

use Magento\Customer\Model\Customer;
use Magento\Catalog\Model\Product;
use Magento\Sales\Model\Order;
use \OpenSubscriptions\OpenSubscriptions\Exception\SubmoduleException;

class Service extends \Magento\Framework\Model\AbstractModel
{
    // used for quote_item_option table
    const QUOTE_OPTION_NAME = 'oa_service';

    const ACTIVE = 'active';
    const PENDING = 'pending';
    const DISABLED = 'disabled';
    const DELETED = 'deleted';

    protected $_cacheTag = 'opensubscriptions_service';
    protected $_eventPrefix = 'opensubscriptions_service';

    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var \OpenSubscriptions\OpenSubscriptions\Model\Connection
     */
    protected $connection;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var Order
     */
    protected $firstOrder;
    /**
     * @var \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting\Collection
     */
    protected $settingsCollection;
    /**
     * Additional settings saved to opensubscriptions_services_settings
     * @var array
     */
    protected $settings = [];

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service');
    }

    public function loadByFakeId($fakeId)
    {
        return $this->load($fakeId, 'fake_id');
    }

    public function isActive()
    {
        return $this->getStatus() === self::ACTIVE;
    }

    public function canRenew()
    {
        return \in_array($this->getStatus(), [self::ACTIVE, self::DISABLED]);
    }
    
    public function getStatusLabel()
    {
        // TODO: translate it
        return $this->getData('status');
    }

    public function setSettings(array $settings)
    {
        foreach ($settings as $k => $v) {
            $this->settings[$k] = $v;
        }
    }

    /**
     * @var Customer
     */
    public function getCustomer(): Customer
    {
        if ($this->customer === null) {
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            // collection because simple load does not load attributes
            $customers = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer\Collection')
                ->addAttributeToSelect('*')
                ->addFieldToFilter('entity_id', $this->getCustomerId());

            $this->customer = $customers->getFirstItem();
        }

        return $this->customer;
    }

    /**
     * @var Order|null
     */
    public function getFirstOrder()
    {
        if ($this->firstOrder === null) {
            $firstOrderId = $this->_getResource()->getFirstOrderId($this->getServiceId());
            if (!$firstOrderId) {
                return null;
            }
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->firstOrder = $objectManager->create('Magento\Sales\Model\Order')
                ->load($firstOrderId);
        }

        return $this->firstOrder;
    }

    /**
     * @var \OpenSubscriptions\OpenSubscriptions\Model\Connection
     */
    public function getConnection(): \OpenSubscriptions\OpenSubscriptions\Model\Connection
    {
        if ($this->connection === null) {
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->connection = $objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\Connection')
                ->load($this->getConnectionId());
        }

        return $this->connection;
    }

    /**
     * @var Product
     */
    public function getProduct(): Product
    {
        if ($this->product === null) {
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->product = $objectManager->create('Magento\Catalog\Model\Product')
                ->load($this->getProductId());
        }

        return $this->product;
    }

    public function getSubmoduleInstance()
    {
        return Submodule::get($this->getSubmodule());
    }

    public function getSubmoduleCommand(string $commandCode): CommandInterface
    {
        $submodule = $this->getSubmoduleInstance();
        switch ($commandCode) {
            case 'create': return $submodule->getCreateCommand();
            case 'delete': return $submodule->getDeleteCommand();
            case 'enable': return $submodule->getEnableCommand();
            case 'disable': return $submodule->getDisableCommand();
            case 'reset-credentials': return $submodule->getResetCredentialsCommand();
            case 'status': return $submodule->getStatusCommand();
        }

        foreach ($submodule->getAdditionalCommands() as $additionalCmd){
            if ($additionalCmd->getId() === $commandCode){
                return $additionalCmd;
            }
        }

        throw new \OpenSubscriptions\OpenSubscriptions\Exception\CommandException('Command not found');
    }

    public function assignOrderItem(\Magento\Sales\Model\Order\Item $item)
    {
        return $this->_getResource()->assignServiceToOrderItem(
            $this->getServiceId(),
            $item->getItemId()
        );
    }

    public function assignProductSettings(\Magento\Catalog\Model\Product $prod)
    {
        foreach ($prod->getData() as $k => $v) {
            if (substr($k, 0, 9) === 'open_sub' && !in_array($k, $this->getAttributesToSkipSave()) && $v) {
                $this->_getResource()->assignServiceSetting($this->getServiceId(), $k, $v);
            }
        }
    }

    /**
     * Returns setting value (from db table opensubscriptions_services_settings)
     */
    public function getSetting(string $name, $default = null)
    {
        foreach ($this->getSettingsCollection() as $setting) {
            if ($setting->getName() === $name) {
                return $setting->getValue();
            }
        }

        return $default;
    }

    public function getSettingsCollection(): \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting\Collection
    {
        if ($this->settingsCollection === null) {
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $this->settingsCollection = $objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Service\Setting\Collection')
                ->addServiceFiler($this->getServiceId());
        }

        return $this->settingsCollection;
    }

    /**
     * // TODO: move to somewhere else
     * @see https://magento.stackexchange.com/questions/106407/how-to-save-customer-attribute-value-in-custom-script-in-magento-2
     */
    public function saveCustomerAttributes(Customer $customer, array $attributes)
    {
        $customerData = $customer->getDataModel();
        $customerData->setId($customer->getId());
        foreach ($attributes as $code => $value) {
            $customerData->setCustomAttribute($code, $value);
        }
        $customer->updateData($customerData);

        // TODO: getResource ??
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerResource = $objectManager->create('Magento\Customer\Model\ResourceModel\Customer');
        foreach ($attributes as $code => $value) {
            $customerResource->saveAttribute($customer, $code);
        }
    }

    /**
     * No need to save additionally in service, as it will be saved anyway
     */
    protected function getAttributesToSkipSave(): array
    {
        return ['open_subscriptions_submodule', 'open_subscriptions_connection_id'];
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
        if (!$this->getFakeId()) {
            // generate random string
            $this->setFakeId(strtoupper(substr(str_shuffle(MD5(microtime())), 0, 10)));
        }

        foreach ($this->settings as $k => $v) {
            $this->_getResource()->assignServiceSetting($this->getServiceId(), $k, $v);
        }

        return parent::beforeSave();
    }
}
