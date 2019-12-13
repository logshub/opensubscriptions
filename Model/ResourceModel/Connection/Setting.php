<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\Connection;

class Setting extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('opensubscriptions_connections_settings', 'setting_id');
    }

    public function saveSetting($connectionId, $name, $value)
    {
        $oaConnectionSettingsTableName = $this->_resources->getTableName('opensubscriptions_connections_settings');

        $sql = "
            INSERT INTO '.$oaConnectionSettingsTableName.'(connection_id,name,value,created_at,updated_at)
            VALUES(?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE value = ?, updated_at = NOW()
        ";
        return $this->getConnection()->query($sql, [
            $connectionId,
            $name,
            $value,
            $value,
        ]);
    }
}
