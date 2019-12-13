<?php
namespace Logshub\OpenSubscriptions\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    // TODO: check it, write test
    public function decrypt(string $encryptedValue)
    {
        return $this->encryptor->decrypt($encryptedValue);
    }

    public function encrypt(string $rawValue)
    {
        return $this->encryptor->encrypt($rawValue);
    }

    public function getParsedSize(int $size, string $unit = "")
    {
        if ((!$unit && $size >= 1<<30) || $unit == "GB") {
            return number_format($size/(1<<30), 2)." GB";
        }
        if ((!$unit && $size >= 1<<20) || $unit == "MB") {
            return number_format($size/(1<<20), 2)." MB";
        }
        if ((!$unit && $size >= 1<<10) || $unit == "kB") {
            return number_format($size/(1<<10), 2)." kB";
        }

        return number_format($size)." bytes";
    }
    
    public function getClientsProductsIdsConfig($storeId = null)
    {
        $value = $this->getConfigValue('opensubscriptions_products/general/client_dashboard_prods', $storeId);
        $ids = \explode(\PHP_EOL, $value);
        if (count($ids) == 1 && !$ids[0]) {
            return [];
        }
        
        return $ids;
    }

    public function getServiceCreationConfig($storeId = null)
    {
        return [
            'product_id' => $this->getConfigValue('opensubscriptions_services/creation/product_id', $storeId),
            'customer_id' => $this->getConfigValue('opensubscriptions_services/creation/customer_id', $storeId),
        ];
    }
    
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }
}
