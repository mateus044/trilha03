<?php

namespace Webjump\WorldPet\Model;

use Magento\Framework\App\RequestInterface;
use Webjump\WorldPet\Api\Data\PetKindInterface;
use Webjump\WorldPet\Api\PetKindRepositoryInterface;
use Webjump\WorldPet\Model\PetKind as PetKindModel;
use Webjump\WorldPet\Model\ResourceModel\PetKind as petResourceModel;
use Webjump\WorldPet\Model\ResourceModel\PetKind\Collection;
use Webjump\WorldPet\Model\ResourceModel\PetKind\CollectionFactory;


class PetKindRepository implements PetKindRepositoryInterface
{

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var petResourceModel
     */
    private petResourceModel $petResourceModel;

    /**
     * @var PetKindInterface
     */
    private PetKindInterface $petKindInterface;

    /**
     * @var PetKindModel
     */
    private PetKindModel $petKindModel;

    /**
     * @var RequestInterface
     */
    private RequestInterface $requestInterface;


    public function __construct(
        Collection $collection,
        petResourceModel $petResourceModel
    ){
        $this->collection        = $collection;
        $this->petResourceModel  = $petResourceModel;
    }


    /**
     * @param int $petKind
     * @return \Magento\Framework\DataObject|mixed|null
     */
    public function getPetKind(int $petKind)
    {
        return $this->collection->getItemById($petKind);
    }

    /**
     * @return array|\Magento\Framework\DataObject[]
     */
    public function getListPetKind()
    {
        return $this->collection->getItems();
    }

    /**
     * @param PetKindInterface $petKind
     * @return bool
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function savePetKind(PetKindInterface $petKind): bool
    {
        $this->petResourceModel->save($petKind);
        return true;
    }

    /**
     * @param PetKindInterface $petKind
     * @return bool
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function updatePetKind(PetKindInterface $petKind): bool
    {
      $update = $this->getPetKind($petKind->getPetKindEntityId());
      if(!is_null($update)){
          $update->addData([
              'pet_kind_name'=>$petKind->getPetKindName(),
              'pet_kind_description'=>$petKind->getPetKindDescription(),
              'created_at' => new \DateTime()
          ]);
          $this->petResourceModel->save($update);
          return true;
      }
        return false;
    }

    /**
     * @param int $petKind
     * @return bool
     * @throws \Exception
     */
    public function deletePetKind(int $petKind)
    {
        $petKind  = $this->collection->getItemById($petKind);
        if(is_null($petKind)){
            return false;
        }
        $this->petResourceModel->delete($petKind);
        return true;
    }
}
