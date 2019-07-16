<?php
namespace Logshub\OpenSubscriptions\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('opensubscriptions_connections')) {
            // TODO: foreign keys

            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_connections').'` (
            `connection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `submodule` varchar(100) NOT NULL,
            `hostname` varchar(100) NOT NULL,
            `port` varchar(20) DEFAULT NULL,
            `secure` tinyint(1) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            `enabled` tinyint(1) default 1,
            `username` varchar(100) DEFAULT NULL,
            `password` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`connection_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);

            // TODO: models and saving after connection edit - hardwrited for now
            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_connections_settings').'` (
            `setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `connection_id` int(10) unsigned NOT NULL,
            `name` varchar(40) NOT NULL,
            `value` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`setting_id`),
            UNIQUE KEY `opensubscriptions_connections_settings_service_setting` (`connection_id`,`name`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;';
            $installer->run($sql);

            // submodule settings for product (saved on product page)
            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_products_settings').'` (
            `product_id` int(10) unsigned NOT NULL,
            `setting` varchar(255) NOT NULL,
            `value` varchar(255) NOT NULL,
            PRIMARY KEY (`product_id`,`setting`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            $installer->run($sql);

            // settings from products definition to make it persistent
            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_services_settings').'` (
            `setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `service_id` int(10) unsigned NOT NULL,
            `name` varchar(40) NOT NULL,
            `value` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`setting_id`),
            UNIQUE KEY `opensubscriptions_services_settings_service_setting` (`service_id`,`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            $installer->run($sql);

            // mapping to order items (and recurring profiles?)
            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_services_order_items').'` (
            `service_id` int(10) unsigned NOT NULL,
            `item_id` int(10) unsigned NOT NULL,
            PRIMARY KEY (`service_id`, `item_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);

            // status: pending, active, disabled, deleted
            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_services').'` (
            `service_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `fake_id` varchar(10) NOT NULL,
            `product_id` int(10) unsigned NOT NULL,
            `connection_id` int(10) unsigned DEFAULT NULL,
            `customer_id` int(10) unsigned NOT NULL,
            `status` varchar(20) NOT NULL,
            `name` varchar(100) DEFAULT NULL,
            `is_created` tinyint(1) NOT NULL DEFAULT 0,
            `external_id` varchar(100) DEFAULT NULL,
            `submodule` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            `expire_at` datetime DEFAULT NULL,
            `username` varchar(100) DEFAULT NULL,
            `password` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`service_id`),
            UNIQUE KEY `idx_fake_id` (`fake_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);

            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_activity_logs').'` (
            `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `created_at` datetime NOT NULL,
            `service_id` int(10) unsigned DEFAULT NULL,
            `creator_admin_id` int(10) unsigned DEFAULT NULL,
            `success` tinyint(1) unsigned DEFAULT NULL,
            `submodule` varchar(255) DEFAULT NULL,
            `action` varchar(255) DEFAULT NULL,
            `message` text,
            PRIMARY KEY (`log_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);

            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_connection_logs').'` (
            `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `created_at` datetime NOT NULL,
            `admin_id` int(10) unsigned DEFAULT NULL,
            `service_id` int(10) unsigned DEFAULT NULL,
            `connection_id` int(10) unsigned DEFAULT NULL,
            `action` varchar(255) NOT NULL,
            `response_code` int(10) NOT NULL,
            `request` text NOT NULL,
            `response` text NOT NULL,
            PRIMARY KEY (`log_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);

            $sql = '
            CREATE TABLE IF NOT EXISTS `'.$installer->getTable('opensubscriptions_services_scheduled_tasks').'` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `service_id` int(10) unsigned NOT NULL,
            `scheduled_date` date NOT NULL,
            `command` varchar(100) NOT NULL,
            `done` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
            $installer->run($sql);
        }
        $installer->endSetup();
    }
}
