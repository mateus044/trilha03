<?php

namespace Webjump\WorldPetCustomer\Api;

use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;

interface PetCustomerManagerInterface
{

    /**
     * @return mixed
     */
    public function managePetCustomerSearch(PetCustomerInterface $petCustomer);

    /**
     * @return array
     */
    public function manageGetListPetCustomer();

    /**
     * @param PetCustomerInterface $petCustomer
     * @return mixed
     */
    public function manageSavePetCustomer(PetCustomerInterface $petCustomer): array;

    /**
     * @param PetCustomerInterface $petCustomer
     * @return array
     */
    public function manageUpdatePetCustomer(PetCustomerInterface $petCustomer): array;

    /**
     * @return mixed
     */
    public function deletePetCustomer(PetCustomerInterface $petCustomer);
}
