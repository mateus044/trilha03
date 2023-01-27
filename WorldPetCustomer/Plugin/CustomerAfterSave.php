<?php

namespace Webjump\WorldPetCustomer\Plugin;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
use Webjump\WorldPetCustomer\Model\PetCustomerManager;

class CustomerAfterSave
{
    /**
     * @var PetCustomerInterface
     */
    private PetCustomerInterface $petCustomerInterface;

    /**
     * @var PetCustomerManager
     */
    private PetCustomerManager $petCustomerManager;

    public function __construct(PetCustomerInterface $petCustomerInterface, PetCustomerManager $petCustomerManager)
    {
        $this->petCustomerInterface = $petCustomerInterface;
        $this->petCustomerManager   = $petCustomerManager;
    }

    public function afterSave(CustomerRepository $customer, CustomerInterface $customerInterface)
    {
       $petCustomer = $this->prepareCustomerInterface($customerInterface);
       $this->petCustomerManager->manageSavePetCustomer($petCustomer);
       return $customerInterface;
    }

    private function prepareCustomerInterface(CustomerInterface $customerInterface): PetCustomerInterface
    {
        $customerId = $customerInterface->getId();
        $petName    = $customerInterface->getCustomAttribute('pets');
        $petKind    = $customerInterface->getCustomAttribute('pet_kind');

        if(!is_null($petName) || !is_null($petKind)) {
            $this->petCustomerInterface->setPetName($petName->getValue());
            $this->petCustomerInterface->setTypePet($petKind->getValue());
            $this->petCustomerInterface->setCustomerId($customerId);
            return $this->petCustomerInterface;
        }
        return $this->petCustomerInterface;
    }
}
