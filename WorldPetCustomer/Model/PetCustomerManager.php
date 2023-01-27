<?php

namespace Webjump\WorldPetCustomer\Model;
use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
use Webjump\WorldPetCustomer\Api\PetCustomerManagerInterface;
use Webjump\WorldPetCustomer\Helper\ConstMessage\ConstMessage;
use Webjump\WorldPetCustomer\Helper\Utils\PatterReturn;
use Webjump\WorldPetCustomer\Model\Validations\FormValidations;
use Webjump\WorldPetCustomer\Helper\Response\PatterResponse;


class PetCustomerManager implements PetCustomerManagerInterface
{
    /**
     * @var PetCustomerRepository
     */
    private PetCustomerRepository $petCustomerRepository;

    /**
     * @var PatterReturn
     */
    private PatterReturn $patterReturn;

    /**
     * @var FormValidations
     */
    private FormValidations $formValidations;

    /**
     * @var PatterResponse
     */
    private  PatterResponse $patterResponse;

    public function __construct(
        PetCustomerRepository $petCustomerRepository,
        PatterReturn $patterReturn,
        FormValidations $formValidations,
        PatterResponse $patterResponse)
    {
        $this->petCustomerRepository = $petCustomerRepository;
        $this->patterReturn    = $patterReturn;
        $this->formValidations = $formValidations;
        $this->patterResponse = $patterResponse;
    }

    public function managePetCustomerSearch(PetCustomerInterface $petCustomer)
    {
        try {
            $entity  = $this->formValidations->validFormForGetEntityId($petCustomer->getData());
            return  $this->gePetKind($entity);
        }catch (\Exception $e){
            return $this->patterReturn->messageError($e->getMessage(),500);
        }
    }

    public function gePetKind(int $entity)
    {
        $entity = $this->petCustomerRepository->getPetCustomer($entity);
        if(is_null($entity)){
            return ConstMessage::PET_NOT_EXISTS;
        }

        $response = $this->patterResponse->responseGetPetCustomer($entity);
        return ['data' => $response];
    }

    public function manageGetListPetCustomer()
    {
        return $this->getListPetCustomer();
    }

    public function getListPetCustomer()
    {
        $pets = $this->petCustomerRepository->getListPetCustomer();
        if(empty($pets)) {
            return ConstMessage::PET_NOT_EXISTS;
        }
        return $this->patterResponse->mountPets($pets);
    }

    public function manageSavePetCustomer(PetCustomerInterface $petCustomer): array
    {
        try {
            $formValidation = $this->formValidations->validFormForSavePet($petCustomer->getData());
            $petCustomerRep = $this->petCustomerRepository->findCustomerById($petCustomer->getCustomerId());
            $typePet = $this->petCustomerRepository->findPetKindById($formValidation['type_pet']);

            if(is_null($typePet))
            {
                return ['data' => ConstMessage::TYPE_PET_NOT_FOUND];
            }
            $this->petCustomerRepository->savePetCustomer($petCustomer);
            return ['data' => ConstMessage::SAVE_SUCCESS];

        }catch (\Exception $e){
            return $this->patterReturn->messageError($e->getMessage(), 500);
        }
    }

    public function manageUpdatePetCustomer(PetCustomerInterface $petCustomer): array
    {
        try {
            $petValidate = $this->formValidations->validFormForUpdatePet($petCustomer);
            $customer  = $this->petCustomerRepository->findCustomerById($petValidate['customer_id']);
            $typePet   = $this->petCustomerRepository->findPetKindById($petValidate['type_pet']);

            if(is_null($typePet))
            {
                return ['data' => ConstMessage::TYPE_PET_NOT_FOUND];
            }

            $update    = $this->petCustomerRepository->updatePetCustomer($petCustomer);
            if(!$update)
            {
                return ['data' => ConstMessage::PET_NOT_EXISTS];
            }

            return ['data' => ConstMessage::UPDATE_SUCCESS];
        }catch (\Exception $e){
            return $this->patterReturn->messageError($e->getMessage(), 500);
        }
    }

    public function deletePetCustomer(PetCustomerInterface $petCustomer): array
    {
        $pet = $this->gePetKind($petCustomer->getData('entity_id'));
        if($pet !== 'PetNotFound'){
            $this->petCustomerRepository->deletePetCustomer($petCustomer->getData('entity_id'));
            return ['data' => ConstMessage::DELETED_SUCCESS];
        }
        return ['data' => $pet];
    }

}
