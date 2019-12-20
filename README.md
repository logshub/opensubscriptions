# Open Subscriptions

The revolutionary Magento module that supports subscriptions and more - also an innovative command and submodule system.

* Home page: https://www.logshub.com/en/opensubscriptions
* Quick guide: https://www.logshub.com/en/opensubscriptions/quick-guide
* Installation: https://www.logshub.com/en/opensubscriptions/installation

## Installation

Be aware that this is APLHA version.

```
composer require logshub/opensubscriptions
php bin/magento setup:di:compile
php bin/magento setup:upgrade
php bin/magento cache:clean
php bin/magento cache:flush
php bin/magento setup:static-content:deploy
```

## License

Open Software License (OSL 3.0)
