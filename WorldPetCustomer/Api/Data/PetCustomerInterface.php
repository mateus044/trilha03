<?php

namespace Webjump\WorldPetCustomer\Api\Data;

interface PetCustomerInterface
{
    /**
     * @const ENTITY_ID
     */
    const ENTITY_ID = 'entity_id';

    /**
     * @const NAME_PET
     */
    const NAME_PET = 'name_pet';

    /**
     * @const TYPE_PET
     */
    const TYPE_PET = 'type_pet';

    /**
     * @const CUSTOMER_ID
     */
    const CUSTOMER_ID = 'customer_id';

    /**
     * @return int
     */
    public function getPetCustomerEntityId(): int;

    /**
     * @return string
     */
    public function getPetName(): string;

    /**
     * @return int
     */
    public function  getTypePet(): int;

    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @param int $entityId
     * @return void
     */
    public function setPetCustomerEntityId(int $entityId): void;

    /**
     * @param string $name
     * @return void
     */
    public function setPetName(string $name): void;

    /**
     * @param int $type
     * @return void
     */
    public function setTypePet(int $type): void;

    /**
     * @param int $customer_id
     * @return void
     */
    public function setCustomerId(int $customer_id): void;
}
