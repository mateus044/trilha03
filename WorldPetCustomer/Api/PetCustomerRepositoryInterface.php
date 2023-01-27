<?php

namespace Webjump\WorldPetCustomer\Api;

use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
//use Webjump\WorldPet\Model\ResourceModel\PetKind\CollectionFactory;

interface PetCustomerRepositoryInterface
{

    /**
     * @param int $petCustomer
     * @return mixed
     */
    public function getPetCustomer(int $petCustomer);

    /**
     * @return array
     */
    public function getListPetCustomer();

    /**
     * @return array
     */
    public function savePetCustomer(PetCustomerInterface $petCustomer): bool;

    /**
     * @param PetCustomerInterface $petCustomer
     * @return bool
     */
    public function updatePetCustomer(PetCustomerInterface $petCustomer): bool;

    /**
     * @param int $petCustomer
     * @return mixed
     */
    public function deletePetCustomer(int $petCustomer);
}
