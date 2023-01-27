<?php

namespace Webjump\WorldPet\Model;

use Webjump\WorldPet\Api\Data\PetKindInterface;
use Webjump\WorldPet\Api\PetKindManagerInterface;
use Webjump\WorldPet\Helper\ConstMessage\ConstMessage;
use Webjump\WorldPet\Helper\Utils\PatterReturn;
use Webjump\WorldPet\Model\Validations\FormValidations;
use Webjump\WorldPet\Helper\Response\PatterResponse;
use Exception;

class PetKindManager implements PetKindManagerInterface
{
    /**
     * @var PetKindRepository
     */
    private PetKindRepository $petKindRepository;

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


    public function __construct(PetKindRepository $petKindRepository, PatterReturn $patterReturn, FormValidations $formValidations, PatterResponse $patterResponse)
    {
        $this->petKindRepository = $petKindRepository;
        $this->patterReturn    = $patterReturn;
        $this->formValidations = $formValidations;
        $this->patterResponse = $patterResponse;
    }

    /**
     * @param PetKindInterface $petKind
     * @return array|string
     */
    public function managePetSearch(PetKindInterface $petKind)
    {
        try {
            $entity = $this->formValidations->validFormForGetEntityId($petKind->getData());
            return $this->getPetKind($entity);

        }catch (\Exception $e){
             return $this->patterReturn->messageError($e->getMessage(),500);
        }
    }

    /**
     * @return array|string
     */
    public function manageGetListPetKind()
    {
        return $this->getListPetKind();
    }

    /**
     * @param PetKindInterface $petKind
     * @return array|mixed|true
     */
    public function manageSavePetKind(PetKindInterface $petKind)
    {
        try {
            $this->formValidations->validFormForSavePet($petKind->getData());
            $this->petKindRepository->savePetKind($petKind);
            return true;
        }catch (\Exception $e){
            return $this->patterReturn->messageError($e->getMessage(), 500);
        }
    }

    /**
     * @param PetKindInterface $petKind
     * @return array|string[]
     * @throws Exception
     */
    public function deletePetKind(PetKindInterface $petKind): array
    {
        $pet = $this->getPetKind($petKind->getData('entity_id'));
        if($pet !== 'PetNotFound'){
           $this->petKindRepository->deletePetKind($petKind->getData('entity_id'));
           return ['data' => ConstMessage::DELETED_SUCCESS];
        }
        return ['data' => $pet];
    }

    /**
     * @param int $entity
     * @return array|string
     */
    public function getPetKind(int $entity)
    {
        $entity = $this->petKindRepository->getPetKind($entity);
        if(is_null($entity)){
            return ConstMessage::PET_NOT_EXISTS;
        }

        $response = $this->patterResponse->responseGetPetKind($entity);
        return $response;
    }

    /**
     * @return array|string
     */
    public function getListPetKind()
    {
        $pets = $this->petKindRepository->getListPetKind();
        if(empty($pets)) {
            return ConstMessage::PET_NOT_EXISTS;
        }
        return $this->patterResponse->mountPets($pets);
    }

    /**
     * @param PetKindInterface $petKind
     * @return array
     */
    public function manageUpdatePetKind(PetKindInterface $petKind): array
    {
        try {
            return $this->updatePetKind($petKind);
        }catch (\Exception $e){
            return $this->patterReturn->messageError($e->getMessage(), 500);
        }
    }

    /**
     * @param $petKind
     * @return array
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function updatePetKind($petKind): array
    {
        $petKind = $this->formValidations->validFormForUpdatePet($petKind);
        $response = $this->petKindRepository->updatePetKind($petKind);
        if($response) {
            return ['data' => ConstMessage::UPDATE_SUCCESS];
        }
        return ['data' => ConstMessage::PET_NOT_EXISTS];
    }
}
