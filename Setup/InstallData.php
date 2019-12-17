<?php
namespace Logshub\OpenSubscriptions\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

/**
 * To repeat installation:
 * 1. remove Attribute Set by UI
 * 2. delete from setup_module where module IN("Logshub_OpenSubscriptions","Logshub_OpenSubscriptionsSubDefault");
 * 3. delete from eav_attribute WHERE attribute_code like "open_sub%";
 * 4. make fix upgrade deploy cache 
 */
class InstallData implements InstallDataInterface
{
    const ATTRIBUTE_SET_NAME = 'Open Subscriptions';
    const ATTRIBUTE_CODE_SUBMODULE = 'open_subscriptions_submodule';
    const ATTRIBUTE_CODE_CONNECTION_ID = 'open_subscriptions_connection_id';

    private $eavSetupFactory;
    private $attributeSetFactory;
    private $attributeSet;
    private $categorySetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // creating ATTRIBUTE SET
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $attributeSet = $this->attributeSetFactory->create();
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $defaultAttributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        try {
            $attributeSet->setData([
                'attribute_set_name' => self::ATTRIBUTE_SET_NAME,
                'entity_type_id' => $entityTypeId,
                'sort_order' => 200,
            ]);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($defaultAttributeSetId);
            $attributeSet->save();
        } catch (\Exception $e){ // already exists
            $attributeSet->load(self::ATTRIBUTE_SET_NAME, 'attribute_set_name');
        }

        // creating PRODUCT ATTRIBUTE
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            self::ATTRIBUTE_CODE_SUBMODULE,
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Submodule',
                'input' => 'select',
                'class' => '',
                'source' => \Logshub\OpenSubscriptions\Model\Catalog\Product\Attribute\Source\Submodule::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => true, // without TRUE, attribute will be added to every attribute set
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'attribute_set' => self::ATTRIBUTE_SET_NAME,
                'note' => 'Behaviour of the service will depends on this setting.',
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            self::ATTRIBUTE_CODE_CONNECTION_ID,
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Connection ID',
                'input' => 'select',
                'class' => '',
                'source' => \Logshub\OpenSubscriptions\Model\Catalog\Product\Attribute\Source\ConnectionId::class,
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
                'attribute_set' => self::ATTRIBUTE_SET_NAME,
                'note' => 'This setting is important only if selected submodule is connecting to remote API.',
            ]
        );

        // adding new Attribute group
        $eavSetup->addAttributeGroup($entityTypeId, $attributeSet->getId(), self::ATTRIBUTE_SET_NAME, 100);

        // Add existing attribute to group
        $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSet->getId(), self::ATTRIBUTE_SET_NAME);
        foreach ([self::ATTRIBUTE_CODE_SUBMODULE, self::ATTRIBUTE_CODE_CONNECTION_ID] as $attrCode) {
            $attributeId = $eavSetup->getAttributeId($entityTypeId, $attrCode);
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSet->getId(), $attributeGroupId, $attributeId, null);
        }

        $setup->endSetup();
    }
}
