<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

class Connection extends \Magento\Framework\Model\AbstractModel
{
    protected $_cacheTag = 'opensubscriptions_conn';
    protected $_eventPrefix = 'opensubscriptions_connection';

    /**
     * @var \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection\Setting\Collection
     */
    protected $settingsCollection;
    /**
     * Additional settings saved to opensubscriptions_connections_settings
     * @var array
     */
    protected $settings = [];

    protected function _construct()
    {
        $this->_init('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection');
    }

    public function setSettings(array $settings)
    {
        foreach ($settings as $k => $v) {
            $this->settings[$k] = $v;
        }
    }

    /**
     * Returns setting value (from db table)
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
 
    public function getSettingsCollection(): \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection\Setting\Collection
    {
        if ($this->settingsCollection === null) {
            // TODO: better way ?
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->settingsCollection = $objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection\Setting\Collection')
                ->addConnectionFiler($this->getConnectionId());
        }

        return $this->settingsCollection;
    }

    public function getFullUrl(): string
    {
        return ($this->getSecure() ? 'https://' : 'http://') .
            $this->getHostname() .
            ($this->getPort() ? ':' . $this->getPort() : '');
    }

    public function addLog(Service $service, string $action, int $responseCode, string $request, string $response)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connectionLog = $objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\ConnectionLog');
        $connectionLog->setData([
            'created_at' => date('Y-m-d H:i:s'),
            'admin_id' => null, // TODO: from session ?? save only in activity log?
            'service_id' => $service->getServiceId(),
            'connection_id' => $this->getConnectionId(),
            'action' => $action,
            'response_code' => $responseCode,
            'request' => $request ? $request : $this->getFullUrl(),
            'response' => $response,
        ]);

        return $connectionLog->save();
    }
    
    public function getSubmoduleConnection(): SubmoduleConnectionInterface
    {
        return Submodule::get($this->getSubmodule())->getConnection($this);
    }
    
    public function saveSubmoduleSettings()
    {
        if (!$this->getSubmodule()) {
            return;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $settingsModel = $objectManager->create('OpenSubscriptions\OpenSubscriptions\Model\Connection\Setting');

        $definitions = $this->getSubmoduleConnection()->getSettingsDefinitions();
        foreach ($definitions as $def) {
            $settingsModel->saveSetting(
                $this->getId(),
                $def->getName(),
                $this->getData($def->getName()) ? $this->getData($def->getName()) : ""
            );
        }
    }
}
