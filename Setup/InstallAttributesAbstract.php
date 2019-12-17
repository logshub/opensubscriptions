<?php
namespace Logshub\OpenSubscriptions\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

abstract class InstallAttributesAbstract implements InstallDataInterface
{
    const BASE_ATTRIBUTE_SET_NAME = 'Open Subscriptions';

    private $eavSetupFactory;
    private $attributeSetFactory;
    private $attributeSet;
    private $categorySetupFactory;
    private $customerSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Returns attribute set to create
     */
    abstract public function getNewAttributeSetName(): string;
    /**
     * Attribute code between 1-30 chars
     * keys that should exists in submodule: type, label, input, source
     */
    abstract public function getNewProductsAttributes(): array;

    /**
     * New attributes for customers, override in your installer
     */
    public function getNewCustomersAttributes(): array
    {
        return [];
    }

    /**
     * Creating attribute set, group and attributes itself
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->processProductAttributes($setup);
        $this->processCustomerAttributes($setup);

        $setup->endSetup();
    }

    protected function processProductAttributes(ModuleDataSetupInterface $setup)
    {
        // creating ATTRIBUTE SET
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSet = $this->createProductAttributeSet($entityTypeId);

        // creating PRODUCT ATTRIBUTE
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        foreach ($this->getNewProductsAttributes() as $attrCode => $attrSettings) {
            try {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attrCode,
                    $this->getDefaultProductAttributes() + $attrSettings
                );
            } catch (\Exception $e) {
                // TODO: better error handling ;]
                var_dump($attrCode);
                var_dump(\get_class($e));
                var_dump($e->getMessage());
            }
        }

        // eg. not created because already created
        if (!$attributeSet->getId()) {
            return;
        }

        // adding new Attribute group
        $eavSetup->addAttributeGroup($entityTypeId, $attributeSet->getId(), $this->getNewAttributeSetName(), 100);

        // Add existing attribute to group
        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSet->getId(), $this->getNewAttributeSetName());
        foreach ($this->getNewProductsAttributes() as $attrCode => $attrSettings) {
            $attributeId = $eavSetup->getAttributeId($entityTypeId, $attrCode);
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSet->getId(), $attributeGroupId, $attributeId, null);
        }
    }

    protected function processCustomerAttributes(ModuleDataSetupInterface $setup)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        foreach ($this->getNewCustomersAttributes() as $attrCode => $attrSettings) {
            try {
                $customerSetup->addAttribute(
                    \Magento\Customer\Model\Customer::ENTITY,
                    $attrCode,
                    $this->getDefaultCustomerAttributes() + $attrSettings
                );
                // add attribute to attribute set
                $attribute = $customerSetup->getEavConfig()
                ->getAttribute(\Magento\Customer\Model\Customer::ENTITY, $attrCode)
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);

                $attribute->save();
            } catch (\Exception $e) {
                // TODO: better error handling ;]
                var_dump($attrCode);
                var_dump(\get_class($e));
                var_dump($e->getMessage());
            }
        }
    }

    protected function createProductAttributeSet(int $entityTypeId)
    {
        $attributeSet = $this->attributeSetFactory->create();
        $defaultAttributeSetId = $this->getDefaultAttributeSetId($entityTypeId);

        try {
            $attributeSet->setData([
                'attribute_set_name' => $this->getNewAttributeSetName(),
                'entity_type_id' => $entityTypeId,
                'sort_order' => 201,
            ]);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($defaultAttributeSetId);
            $attributeSet->save();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // TODO: better error handling ;]
            if (\strpos($e->getMessage(), 'already exists')) {
                $attributeSet = $this->getAttributeSetByName($this->getNewAttributeSetName());
            }
            var_dump($e->getMessage());
        }

        return $attributeSet;
    }

    protected function getDefaultAttributeSetId(int $entityTypeId): int
    {
        $setCollection = $this->attributeSetFactory->create()->getCollection();
        $setCollection->addFieldToFilter('entity_type_id', $entityTypeId);
        $setCollection->addFieldToFilter('attribute_set_name', self::BASE_ATTRIBUTE_SET_NAME);
        foreach ($setCollection as $attributeSet) {
            return $attributeSet->getId();
        }

        // TODO: catch it, as if thrown it will block whole store??
        throw new \Exception('Unable to get default attribute set');
    }

    /**
     * Retrieve attribute set based on given name.
     * This utility methods assumes that there is only one attribute set with given name,
     *
     * @param string $attributeSetName
     * @return \Magento\Eav\Model\Entity\Attribute\Set|null
     */
    protected function getAttributeSetByName($attributeSetName)
    {
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Eav\Model\Entity\Attribute\Set $attributeSet */
        // $attributeSet = $objectManager->create(\Magento\Eav\Model\Entity\Attribute\Set::class)
        $attributeSet = $this->attributeSetFactory->create()
            ->load($attributeSetName, 'attribute_set_name');
        if ($attributeSet->getId() === null) {
            return null;
        }
        return $attributeSet;
    }

    /**
     * keys that should exists in submodule: type, label, input, source
     */
    protected function getDefaultProductAttributes()
    {
        return [
            'backend' => '',
            'frontend' => '',
            'class' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true, // without TRUE, attribute will be added to every attribute set
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'apply_to' => '',
            'attribute_set' => $this->getNewAttributeSetName(),
        ];
    }

    /**
     * keys that should exists in submodule: type, label, input
     */
    protected function getDefaultCustomerAttributes()
    {
        return [
            'required' => false,
            'visible' => true,
            'user_defined' => true, // without TRUE, attribute will be added to every attribute set
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
        ];
    }
}
