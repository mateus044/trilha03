<?php


namespace Webjump\WorldPetCustomer\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Webjump\WorldPetCustomer\Model\Config\Source\Options;

class AddPetEntityToCustomer implements DataPatchInterface
{
    const ATTRIBUTE_PET_KIND = 'pet_kind';

    private ModuleDataSetupInterface $moduleDataSetup;

    private EavSetupFactory $eavSetupFactory;

    private EavConfig $eavConfig;

    private AttributeSetFactory $attributeSetFactory;

    private CustomerSetupFactory $customerSetupFactory;

    /**
     * AddPetEntityToCustomer constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     * @param EavConfig                $eavConfig
     * @param AttributeSetFactory      $attributeSetFactory
     * @param CustomerSetupFactory     $customerSetupFactory
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        EavConfig $eavConfig,
        AttributeSetFactory $attributeSetFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->addPetKind();
        $this->defineRelationshipAttributePetKind();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Method Description
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    private function addPetKind()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_PET_KIND,
            [
                'type'      => 'text',
                'label'     => __('Pet Kind'),
                'input'     => 'select',
                'visible'   => true,
                'position'  => 320,
                'required'  => false,
                'system'    => false,
                'source'    => Options::class,
            ]
        );
    }

    /**
     * Method to define relationship of attribute
     *
     * @codeCoverageIgnore
     */
    private function defineRelationshipAttributePetKind(): void
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(
                Customer::ENTITY,
                self::ATTRIBUTE_PET_KIND
            )
            ->addData(
                [
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => [
                        'adminhtml_customer',
                        'customer_account_create',
                        'customer_account_edit'
                    ],
                ]
            );

        $attribute->save();
    }

    /**
     * Method to revert alterations in database
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->removeAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_PET_KIND
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
