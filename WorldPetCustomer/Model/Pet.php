<?php

namespace Webjump\WorldPetCustomer\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet as ResourceModel;

class Pet extends AbstractExtensibleModel implements PetCustomerInterface
{
    /**
     * Construct method from Pet Model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getPetCustomerEntityId(): int
    {
       return $this->getData(self::ENTITY_ID);
    }

    public function getPetName(): string
    {
        return $this->getData(self::NAME_PET);
    }

    public function getTypePet(): int
    {
        return $this->getData(self::TYPE_PET);
    }

    public function getCustomerId(): int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setPetCustomerEntityId(int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    public function setPetName(string $name): void
    {
        $this->setData(self::NAME_PET, $name);
    }

    public function setTypePet(int $type): void
    {
        $this->setData(self::TYPE_PET, $type);
    }

    public function setCustomerId(int $customer_id): void
    {
        $this->setData(self::CUSTOMER_ID, $customer_id);
    }
}
