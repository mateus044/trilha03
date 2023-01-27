<?php

namespace Webjump\WorldPetCustomer\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Webjump\WorldPetCustomer\Model\PetCustomerManager;
use Webjump\WorldPetCustomer\Api\Data\PetCustomerInterface;
use Webjump\WorldPetCustomer\Helper\Response\PatterResponse;
use Webjump\WorldPetCustomer\Helper\Utils\PatterReturn;
use Webjump\WorldPetCustomer\Model\PetCustomerRepository;
use Webjump\WorldPetCustomer\Model\Validations\FormValidations;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet\Collection;
use Webjump\WorldPetCustomer\Model\Pet;

class PetCustomerManagerTest extends TestCase
{
    /**
     * @var Collection
     */
    private Collection $collection;

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
    private PatterResponse $patterResponse;

    /**
     * @var PetCustomerInterface
     */
    private PetCustomerInterface $petCustomerInterface;


    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->pet = $this->createMock(Pet::class);
        $this->collection = $this->createMock(Collection::class);
        $this->petCustomerRepository = $this->createMock(PetCustomerRepository::class);
        $this->patterReturn          = $this->createMock(PatterReturn::class);
        $this->formValidations       = $this->createMock(FormValidations::class);
        $this->patterResponse        = $this->createMock(PatterResponse::class);
        $this->petCustomerInterface  = $this->getMockBuilder(PetCustomerInterface::class)
             ->setMethods([
                 'getPetCustomerEntityId',
                 'getPetName',
                 'getTypePet',
                 'getCustomerId',
                 'setPetCustomerEntityId',
                 'setPetName',
                 'setTypePet',
                 'setCustomerId',
                 'getData',
                 'getPetKind',
                 'getPetCustomer',
                 'getListPetCustomer',
                 'updatePetCustomer',
                 'gePetKind',
                 'findPetKindById'
             ])->getMock();

        $this->testSubject = new PetCustomerManager(
            $this->petCustomerRepository,
            $this->patterReturn,
            $this->formValidations,
            $this->patterResponse
        );
    }

    public function testManagePetCustomerSearch() //OK
    {
        $array = [];
        $entity_id = 16;
        $arrayResponse = ['data' => []];

        $this->petCustomerInterface
             ->expects(self::any())
             ->method('setPetCustomerEntityId')
             ->with($entity_id);

        $this->petCustomerInterface
             ->expects(self::any())
             ->method('getData')
             ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForGetEntityId')
            ->with($array)
            ->willReturn($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getPetCustomer')
            ->willReturn($array);

        $this->patterResponse
            ->expects(self::any())
            ->method('responseGetPetCustomer')
            ->willReturn($array);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->managePetCustomerSearch($response);
        $this->assertEquals($arrayResponse, $result);
    }

    public function testManagePetCustomerSearchWhenPetCustomerNotExist()
    {
        $array = [];
        $entity_id = 1000;
        $petNotFound =  'PetNotFound';

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForGetEntityId')
            ->with($array)
            ->willReturn($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getPetKind')
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getPetCustomer')
            ->willReturn(null);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->managePetCustomerSearch($response);
        $this->assertEquals($petNotFound, $result);
    }

    public function testManagePetCustomerSearchWithException()
    {
        $array = [];
        $entity_id = 1000;

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForGetEntityId')
            ->with($array)
            ->willThrowException(new \Exception( __('required')));

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->managePetCustomerSearch($response);
        $this->assertEquals($array, $result);
    }

    public function testManageGetListPetCustomer()
    {
        $array = [];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getListPetCustomer')
            ->with($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getListPetCustomer')
            ->willReturn($this->collection);

        $this->patterResponse
            ->expects(self::any())
            ->method('mountPets')
            ->willReturn($array);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageGetListPetCustomer($response);
        $this->assertEquals($array, $result);
    }

    public function testManageGetListPetCustomerWhenPetNotFound()
    {
        $array = [];
        $result = 'PetNotFound';

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getListPetCustomer')
            ->with($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getListPetCustomer')
            ->willReturn(null);

        $this->patterResponse
            ->expects(self::any())
            ->method('mountPets')
            ->willReturn($array);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageGetListPetCustomer($response);
        $this->assertEquals($result, $result);
    }

    public function testManageUpdatePetCustomer()
    {
        $array     = [];
        $petName   = 'doc';
        $typePet   = 1;
        $customer  = 1;
        $entity_id = 1;
        $result    = ['data' => 'Pet Kind updated'];
        $customerArray  = ['customer_id' => 1, 'type_pet'=>1];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petCustomerInterface)
            ->willReturn($customerArray);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findCustomerById')
            ->with($customerArray['customer_id'])
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findPetKindById')
            ->with($customerArray['type_pet'])
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('updatePetCustomer')
            ->with($this->petCustomerInterface)
            ->willReturn(true);


        $response = $this->petCustomerInterface;
        $update   = $this->testSubject->manageUpdatePetCustomer($response);
        $this->assertEquals($result , $update);
    }

    public function testManageUpdatePetCustomerWithException()
    {
        $array = [];
        $petName = 'doc';
        $typePet = 1;
        $customer = 1;
        $entity_id = 1;

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petCustomerInterface)
            ->willThrowException(new \Exception( __('required')));

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageUpdatePetCustomer($response);
        $this->assertEquals($array, $result);
    }

    public function testManageUpdatePetCustomerWhenPetNotExist()
    {
        $array     = [];
        $petName   = 'doc';
        $typePet   = 1;
        $customer  = 1;
        $entity_id = 1;
        $result    = ['data' => 'PetNotFound'];
        $customerArray  = ['customer_id' => 1, 'type_pet' => 1];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petCustomerInterface)
            ->willReturn($customerArray);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findCustomerById')
            ->with($customerArray['customer_id'])
            ->willReturn(null);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findPetKindById')
            ->with($customerArray['type_pet'])
            ->willReturn($array);


        $response = $this->petCustomerInterface;
        $update   = $this->testSubject->manageUpdatePetCustomer($response);
        $this->assertEquals($result, $update);
    }

    public function testManageUpdatePetCustomerWhenTypePetNotExist()
    {
        $array     = [];
        $petName   = 'doc';
        $typePet   = 1;
        $customer  = 1;
        $entity_id = 1;
        $result    = ['data' => 'TypePetNotFound'];
        $customerArray  = ['customer_id' => 1, 'type_pet' => 1];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForUpdatePet')
            ->with($this->petCustomerInterface)
            ->willReturn($customerArray);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findCustomerById')
            ->with($customerArray['customer_id'])
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findPetKindById')
            ->with($customerArray['type_pet'])
            ->willReturn(null);


        $response = $this->petCustomerInterface;
        $update   = $this->testSubject->manageUpdatePetCustomer($response);
        $this->assertEquals($result, $update);
    }

    public function testManageSavePetCustomer()
    {
        $array = [];
        $petName = 'doc';
        $typePet = 1;
        $customer = 1;
        $entity_id = 1;
        $formValidation = ['type_pet' => 1];
        $petResponse = [ 'data' => 'Pet Kind saved successfully'];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForSavePet')
            ->with($array)
            ->willReturn($formValidation);


        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getCustomerId')
            ->willReturn($entity_id);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findCustomerById')
            ->with($entity_id);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findPetKindById')
            ->with($formValidation['type_pet'])
            ->willReturn($array);


        $this->petCustomerRepository
            ->expects(self::any())
            ->method('savePetCustomer')
            ->with($this->petCustomerInterface);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageSavePetCustomer($response);
        $this->assertEquals($petResponse, $result);
    }

    public function testManageSavePetCustomerWhenTypePetNotExist()
    {
        $array = [];
        $petName = 'doc';
        $typePet = 1;
        $customer = 1;
        $entity_id = 1;
        $formValidation = ['type_pet' => 1];
        $petResponse = ['data' => 'TypePetNotFound'];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForSavePet')
            ->with($array)
            ->willReturn($formValidation);


        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getCustomerId')
            ->willReturn($entity_id);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findCustomerById')
            ->with($entity_id);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('findPetKindById')
            ->with($formValidation['type_pet'])
            ->willReturn(null);


        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageSavePetCustomer($response);
        $this->assertEquals($petResponse, $result);
    }

    public function testManageSavePetCustomerWhenException()
    {
        $array = [];
        $petName = 'doc';
        $typePet = 1;
        $customer = 1;
        $entity_id = 1;
        $petResponse = [ 'data' => 'Pet Kind saved successfully'];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetName')
            ->with($petName);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setTypePet')
            ->with($typePet);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($customer);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($array);

        $this->formValidations
            ->expects(self::any())
            ->method('validFormForSavePet')
            ->with($array)
            ->willThrowException(new \Exception( __('required')));

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->manageSavePetCustomer($response);
        $this->assertEquals($array, $result);
    }

    public function testDeletePetCustomer()
    {
        $array = [];
        $entity_id = 1;
        $petResponse = ['data' => 'Pet Kind deleted '];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('gePetKind')
            ->with($entity_id)
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getPetCustomer')
            ->with($entity_id)
            ->willReturn($this->petCustomerInterface);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('deletePetCustomer')
            ->with($entity_id)
            ->willReturn(true);


        $response = $this->petCustomerInterface;
        $result = $this->testSubject->deletePetCustomer($response);
        $this->assertEquals($petResponse, $result);
    }

    public function testDeletePetCustomerNotFound()
    {
        $array = [];
        $entity_id = 1;
        $petResponse = ['data' => 'PetNotFound'];

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('setPetCustomerEntityId')
            ->with($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('getData')
            ->willReturn($entity_id);

        $this->petCustomerInterface
            ->expects(self::any())
            ->method('gePetKind')
            ->with($entity_id)
            ->willReturn($array);

        $this->petCustomerRepository
            ->expects(self::any())
            ->method('getPetCustomer')
            ->with($entity_id)
            ->willReturn(null);

        $response = $this->petCustomerInterface;
        $result = $this->testSubject->deletePetCustomer($response);
        $this->assertEquals($petResponse, $result);
    }


}
