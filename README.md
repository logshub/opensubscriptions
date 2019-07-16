# Open Subscriptions

## Requirements

* Magento 2.2.4 (test more)
* PHP 7.1 (test more)
* MySQL 5.5+

## Installation

```
composer require logshub/open-subscriptions
php bin/magento setup:upgrade
php bin/magento module:status
php bin/magento cache:clean
php bin/magento cache:flush
php bin/magento setup:static-content:deploy
php bin/magento indexer:reindex
```

## Uninstalling

```
composer remove logshub/open-subscriptions
php bin/magento setup:upgrade
php bin/magento cache:clean
php bin/magento cache:flush
php bin/magento setup:static-content:deploy
php bin/magento indexer:reindex
```

## Submodules required files

```
Model/Connection.php
Model/Submodule.php
Setup/InstallData.php
etc/module.xml
README.md
composer.json
registration-opensubscriptions.php
registration.php
```

## FAQ

**How to add attributes for my submodules?**

Use standard Magento's attributes and attribute sets for products.

## TODO

* product BOOL attribute to determine whether it is monthly payment (templates/checkout/cart/item/default.phtml)
