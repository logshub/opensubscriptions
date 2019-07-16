<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel;

class Service extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_services', 'service_id');
    }

    /**
     * @param int $serviceId
     * @return int
     */
    public function getFirstOrderId(int $serviceId): int
    {
        $oaItemTableName = $this->_resources->getTableName('opensubscriptions_services_order_items');
        $orderItemsTableName = $this->_resources->getTableName('sales_order_item');
        $sql = '
            SELECT oi.order_id
            FROM '.$oaItemTableName.' AS oaoi
            JOIN '.$orderItemsTableName.' AS oi ON oi.item_id = oaoi.item_id
            WHERE oaoi.service_id = ?
            ORDER BY oi.created_at ASC
            LIMIT 1;
        ';

        $data = $this->getConnection()->fetchRow($sql, [$serviceId]);
        if (!empty($data['order_id'])) {
            return $data['order_id'];
        }

        return 0;
    }
    
    /**
     * 
     * @param int $orderId
     * @return \Logshub\OpenSubscriptions\Model\ResourceModel\Service\Collection
     */
    public function getOrderServices(int $orderId): \Logshub\OpenSubscriptions\Model\ResourceModel\Service\Collection
    {
        $oaItemTableName = $this->_resources->getTableName('opensubscriptions_services_order_items');
        $orderItemsTableName = $this->_resources->getTableName('sales_order_item');
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->create('Logshub\OpenSubscriptions\Model\ResourceModel\Service\Collection');
        $collection->getSelect()
            ->join(['oaoi' => $oaItemTableName], 'main_table.service_id = oaoi.service_id', [])
            ->join(['oi' => $orderItemsTableName], 'oi.item_id = oaoi.item_id', [])
            ->where('oi.order_id = ?', $orderId);
        
        return $collection;
    }

    public function assignServiceToOrderItem(int $serviceId, int $orderItemId)
    {
        $oaItemTableName = $this->_resources->getTableName('opensubscriptions_services_order_items');

        $sql = '
            INSERT INTO '.$oaItemTableName.'(service_id, item_id)
            VALUES(?, ?)
            ON DUPLICATE KEY UPDATE item_id = item_id
        ';

        return $this->getConnection()->query($sql, [$serviceId, $orderItemId]);
    }

    public function assignServiceSetting(int $serviceId, string $setting, string $value)
    {
        $oaServiceSettingsTableName = $this->_resources->getTableName('opensubscriptions_services_settings');

        $sql = '
            INSERT INTO '.$oaServiceSettingsTableName.'(service_id, name, value, created_at, updated_at)
            VALUES(?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE value = ?, updated_at = NOW()
        ';

        return $this->getConnection()->query($sql, [
            $serviceId,
            $setting,
            $value,
            $value,
        ]);
    }
    
    public function countCustomerServices(int $customerId): int
    {
        $oaServicesTableName = $this->_resources->getTableName('opensubscriptions_services');

        $sql = '
            SELECT COUNT(service_id) as total
            FROM '.$oaServicesTableName.'
            WHERE customer_id = ? AND status != ?;
        ';

        return (int)$this->getConnection()->fetchOne($sql, [$customerId, \Logshub\OpenSubscriptions\Model\Service::DELETED]);
    }
}
