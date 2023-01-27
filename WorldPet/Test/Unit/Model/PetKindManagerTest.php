<?php

namespace Webjump\WorldPet\Test\Unit\Model;


use PHPUnit\Framework\TestCase;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Webjump\WorldPet\Api\Data\PetKindInterface;
use Webjump\WorldPet\Model\PetKind;
use Magento\Framework\Api\SearchResults;
use Webjump\WorldPet\Model\PetKindRepository;
use Webjump\WorldPet\Model\PetKindManager;
use Webjump\WorldPet\Model\ResourceModel\PetKind as PetKindResourceModel;
use Webjump\WorldPet\Model\ResourceModel\PetKind\Collection;
use Webjump\WorldPet\Model\ResourceModel\PetKind\CollectionFactory;
use Webjump\WorldPet\Helper\Utils\PatterReturn;
use Webjump\WorldPet\Model\Validations\FormValidations;
use Webjump\WorldPet\Helper\Response\PatterResponse;

class PetKindManagerTest extends TestCase
{
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

    /**
     * @var PetKindRepository
     */
    private PetKindRepository $petKindRepository;

    /**
     * @var PetKindManager
     */
    private PetKindManager $petKindManager;

    /**
     * @var PetKindResourceModel
     */
    private PetKindResourceModel $petKindResourceModel;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var SearchResults
     */
    private SearchResults $searchResultsFactory;

    /**
     * @return void
     */
    public function setUp(): void
    {

        $this->collection = $this->createMock(Collection::class);
        $this->petKindRepository = $this->createMock(PetKindRepository::class);
        $this->patterReturn = $this->createMock(PatterReturn::class);
        $this->formValidations = $this->createMock(FormValidations::class);
        $this->patterResponse = $this->createMock(PatterResponse::class);
        $this->petKindInterface = $this->getMockBuilder(PetKindInterface::class)
            ->setMethods([
                'getPetKindEntityId',
                'setPetKindEntityId',
                'getData',
                'getPetKindName',
                'setPetKindName',
                'setPetKindDescription',
                'getPetKindDescription',
                'savePetKind',
                'getPetKind',
                'validFormForSavePet',
                'validFormForGetEntityId',
                'getListPetKind',
                'updatePetKind'
                ])
            ->getMock();

        $this->petKind = $this->createMock(PetKind::class);

        $this->testSubject = new PetKindManager(
            $this->petKindRepository,
            $this->patterReturn,
            $this->formValidations,
            $this->patterResponse
        );
    }


    public function testManagePetSearch()// OK
    {
        $entityId = 1;
        $array    = [];
        $arrayResponse = [];

        $this->petKindInterface
             ->expects(self::any())
             ->method('setPetKindEntityId')
             ->with($entityId);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
             ->expects(self::any())
             ->method('validFormForGetEntityId')
             ->with($array)
             ->willReturn($entityId);

        $this->petKindInterface
             ->expects(self::any())
             ->method('getPetKind')
             ->willReturn($array);

        $this->petKindRepository
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn($array);

        $this->patterResponse
            ->expects(self::any())
            ->method('responseGetPetKind')
            ->willReturn($array);

        $response = $this->petKindInterface;
        $result = $this->testSubject->managePetSearch($response);
        $this->assertEquals($arrayResponse, $result);
    }

    public function testManagePetSearchWhenPetKindNotExist()// OK
    {
        $entityId = 1;
        $array  = [];
        $stringResponse = 'PetNotFound';

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindEntityId')
            ->with($entityId);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForGetEntityId')
            ->with($array)
            ->willReturn($entityId);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn($array);

        $this->petKindRepository
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn(null);

        $response = $this->petKindInterface;
        $result = $this->testSubject->managePetSearch($response);
        $this->assertEquals($stringResponse, $result);
    }

    public function testManagePetSearchWithExeption()// OK
    {
        $entityId = null;
        $array  = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindEntityId')
            ->with($entityId);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForGetEntityId')
            ->with($array)
            ->willThrowException(new \Exception( __('required')));

        $response = $this->petKindInterface;
        $result = $this->testSubject->managePetSearch($response);
        $this->assertEquals($array, $result);
    }


    public function testManageSavePetKind() //OK
    {
        $petName = "test";
        $description = "desc";
        $array = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindName')
            ->with($petName);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindDescription')
            ->with($description);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->petKindInterface
            ->expects(self::any())
            ->method('savePetKind')
            ->willReturn(true);

        $response = $this->petKindInterface;
        $result = $this->testSubject->manageSavePetKind($response);
        $this->assertEquals(true, $result);
    }

    public function testManageSavePetKindWithException()//OK
    {
        $petName = null;
        $description = null;
        $array = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindName')
            ->with($petName);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindDescription')
            ->with($description);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForSavePet')
            ->with($array)
            ->willThrowException(new \Exception( __('required')));

        $response = $this->petKindInterface;
        $result = $this->testSubject->manageSavePetKind($response);
        $this->assertEquals($array, $result);
    }

    public function testManageGetListPetKind() //OK
    {
        $array = [];
        $this->petKindInterface
             ->expects(self::any())
             ->method('getListPetKind')
             ->willReturn($array);

        $this->petKindRepository
             ->expects(self::any())
             ->method('getListPetKind')
             ->willReturn($this->collection);

        $result = $this->testSubject->manageGetListPetKind();
        $this->assertEquals($array, $result);
    }

    public function testManageGetListPetKindWhenIsNull() //OK
    {
        $array = [];
        $stringResponse = 'PetNotFound';

        $this->petKindInterface
            ->expects(self::any())
            ->method('getListPetKind')
            ->willReturn($array);

        $this->petKindRepository
            ->expects(self::any())
            ->method('getListPetKind')
            ->willReturn(null);

        $result = $this->testSubject->manageGetListPetKind();
        $this->assertEquals($stringResponse, $result);
    }

    public function testDeletePetKind() //OK
    {
        $array = [];
        $entity = 10;
        $arrayResponse = ['data' => 'Pet Kind deleted'];

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($entity);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getPetKind')
            ->with($entity)
            ->willReturn($array);

        $this->petKindRepository
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn($array);

        $this->petKindRepository
            ->expects(self::any())
            ->method('deletePetKind')
            ->willReturn(true);

        $response = $this->petKindInterface;
        $result = $this->testSubject->deletePetKind($response);
        $this->assertEquals($arrayResponse, $result);
    }

    public function testDeletePetKindWhenPetKindNotExist() //OK
    {
        $stringResponse = 'PetNotFound';
        $entity = 10;
        $arrayResponse = ['data' => 'PetNotFound'];

        $this->petKindInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($entity);

        $this->petKindInterface
            ->expects(self::any())
            ->method('getPetKind')
            ->with($entity)
            ->willReturn($stringResponse);

        $response = $this->petKindInterface;
        $result = $this->testSubject->deletePetKind($response);
        $this->assertEquals($arrayResponse, $result);
    }

    public function testManageUpdatePetKind() //OK
    {
        $arrayResponse = ['data' => 'Pet Kind updated'];
        $entity_id = 10;
        $petName = "Mamifero";
        $description = "boi";
        $array = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindEntityId')
            ->with($entity_id);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindName')
            ->with($petName);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindDescription')
            ->with($description);

        $this->petKindInterface
             ->expects(self::any())
             ->method('updatePetKind')
             ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petKindInterface)
            ->willReturn($this->petKindInterface);

        $this->petKindRepository
            ->expects(self::any())
            ->method('updatePetKind')
            ->with($this->petKindInterface)
            ->willReturn(true);

        $response = $this->petKindInterface;
        $result = $this->testSubject->manageUpdatePetKind($response);
        $this->assertEquals($arrayResponse, $result);
    }

    public function testManageUpdatePetKindWithException() //OK
    {

        $entity_id = 10;
        $petName = "Mamifero";
        $description = "boi";
        $array = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindEntityId')
            ->with($entity_id);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindName')
            ->with($petName);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindDescription')
            ->with($description);

        $this->petKindInterface
            ->expects(self::any())
            ->method('updatePetKind')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petKindInterface)
            ->willThrowException(new \Exception( __('Entity_id not found')));


        $response = $this->petKindInterface;
        $result = $this->testSubject->manageUpdatePetKind($response);
        $this->assertEquals($array, $result);
    }

    public function testManageUpdatePetKindWhenPetJindNotExist() //Aqui
    {
        $arrayResponse = ['data'=>'PetNotFound'];
        $entity_id = 10;
        $petName = "Mamifero";
        $description = "boi";
        $array = [];

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindEntityId')
            ->with($entity_id);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindName')
            ->with($petName);

        $this->petKindInterface
            ->expects(self::any())
            ->method('setPetKindDescription')
            ->with($description);

        $this->petKindInterface
            ->expects(self::any())
            ->method('updatePetKind')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petKindInterface)
            ->willReturn($this->petKindInterface);

        $this->petKindRepository
             ->expects(self::any())
             ->method('updatePetKind')
             ->with($this->petKindInterface)
             ->willReturn(false);

        $response = $this->petKindInterface;
        $result = $this->testSubject->manageUpdatePetKind($response);
        $this->assertEquals($arrayResponse, $result);
    }
}
