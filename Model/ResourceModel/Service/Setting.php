<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\Service;

class Setting extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_services_settings', 'setting_id');
    }

    /**
     * @param int $serviceId
     * @return int
     */
    // public function getFirstOrderId(int $serviceId): int
    // {
    //     $sql = '
    //         SELECT oi.order_id
    //         FROM opensubscriptions_services_order_items AS oaoi
    //         JOIN sales_order_item AS oi ON oi.item_id = oaoi.item_id
    //         WHERE oaoi.service_id = ?
    //         ORDER BY oi.created_at ASC
    //         LIMIT 1;
    //     ';
    //
    //     $data = $this->getConnection()->fetchRow($sql, [$serviceId]);
    //     if (!empty($data['order_id'])) {
    //         return $data['order_id'];
    //     }
    //
    //     return 0;
    // }
    //
    // public function assignServiceSetting(int $serviceId, string $setting, string $value)
    // {
    //     $sql = '
    //         INSERT INTO opensubscriptions_services_settings(service_id, setting, value)
    //         VALUES(?, ?, ?)
    //         ON DUPLICATE KEY UPDATE value = ?
    //     ';
    //
    //     return $this->getConnection()->query($sql, [
    //         $serviceId,
    //         $setting,
    //         $value,
    //         $value,
    //     ]);
    // }
}
