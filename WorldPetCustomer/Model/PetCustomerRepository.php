<?php

namespace Webjump\WorldPetCustomer\Model;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
use Webjump\WorldPetCustomer\Api\PetCustomerRepositoryInterface;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet\Collection;
use Webjump\WorldPetCustomer\Model\Pet as PetModel;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet\CollectionFactory;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet as petResourceModel;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Webjump\WorldPet\Model\PetKindRepository;

class PetCustomerRepository implements PetCustomerRepositoryInterface
{
    /**
     * @var PetKindRepository
     */
    private PetKindRepository $petKindRepository;

    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var PetCustomerInterface
     */
    private PetCustomerInterface $petCustomerInterface;

    /**
     * @var Pet
     */
    private PetModel $petModel;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $requestInterface;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var PetFactory
     */
    private PetFactory $petFactory;

    /**
     * @var petResourceModel
     */
    private petResourceModel $petResourceModel;

    public function __construct(
        petResourceModel $petResourceModel ,
        PetFactory $petFactory,
        Context $context,
        CollectionFactory $collectionFactory,
        Collection $collection,
        PetCustomerInterface $petCustomerInterface,
        PetModel $petModel,
        RequestInterface $requestInterface,
        CustomerRepository $customerRepository,
        PetKindRepository $petKindRepository
    )
    {
        $this->collection  = $collection;
        $this->petCustomerInterface = $petCustomerInterface;
        $this->petModel = $petModel;
        $this->collectionFactory = $collectionFactory;
        $this->requestInterface = $requestInterface;
        $this->context = $context;
        $this->petFactory = $petFactory;
        $this->petResourceModel = $petResourceModel;
        $this->customerRepository = $customerRepository;
        $this->petKindRepository  = $petKindRepository;
    }

    public function getPetCustomer(int $petCustomer)
    {
        return  $this->collection->getItemById($petCustomer);
    }

    public function getListPetCustomer()
    {
        return $this->collection->getItems();
    }

    public function savePetCustomer(PetCustomerInterface $petCustomer): bool
    {
        $this->petResourceModel->save($petCustomer);
        return true;
    }

    public function updatePetCustomer(PetCustomerInterface $petCustomer): bool
    {
        $update = $this->getPetCustomer($petCustomer->getPetCustomerEntityId());
        if(!is_null($update)){
            $update->addData([
                'name_pet'=>$petCustomer->getPetName(),
                'type_pet'=>$petCustomer->getTypePet(),
                'customer_id'=>$petCustomer->getCustomerId()
            ]);
            $this->petResourceModel->save($update);
            return true;
        }
        return false;
    }

    public function deletePetCustomer(int $petCustomer): bool
    {
        $petCustomer  = $this->collection->getItemById($petCustomer);
        if(is_null($petCustomer)){
            return false;
        }
        $this->petResourceModel->delete($petCustomer);
        return true;
    }

    public function findCustomerById(int $customerID)
    {
        return $this->customerRepository->getById($customerID);
    }

    public function findPetKindById(int $type_pet)
    {
        return $this->petKindRepository->getPetKind($type_pet);
    }
}
