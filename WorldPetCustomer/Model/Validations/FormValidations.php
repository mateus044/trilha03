<?php

namespace Webjump\WorldPetCustomer\Model\Validations;
use Exception;
use Webjump\WorldPetCustomer\Helper\ConstMessage\ConstMessage;

class FormValidations
{
    /**
     * @param array $petCustomer
     * @return mixed
     * @throws Exception
     */
    public function validFormForGetEntityId(array $petCustomer)
    {
        if(!isset($petCustomer['entity_id'])){
            throw new Exception(ConstMessage::ENTITY_ID_NOT_FOUND,404);
        }

        if(!is_integer($petCustomer['entity_id'])){
            throw new Exception(ConstMessage::ONLY_INTEGER,404);
        }

        return $petCustomer['entity_id'];
    }

    /**
     * @param array $petCustomer
     * @return array
     * @throws Exception
     */
    public function validFormForSavePet(array $petCustomer)
    {
        if(!isset($petCustomer['name_pet'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_string($petCustomer['name_pet'])){
            throw new Exception(ConstMessage::ONLY_STRING,400);
        }

        if(!isset($petCustomer['customer_id'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_integer($petCustomer['customer_id'])){
            throw new Exception(ConstMessage::ONLY_INTEGER,400);
        }

        if(!isset($petCustomer['type_pet'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_integer($petCustomer['type_pet'])){
            throw new Exception(ConstMessage::ONLY_INTEGER,400);
        }
        return $petCustomer;
    }

    /**
     * @param $petCustomer
     * @return mixed
     * @throws Exception
     */
    public function validFormForUpdatePet($petCustomer)
    {
        if(!isset($petCustomer['entity_id'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_integer($petCustomer['entity_id'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!isset($petCustomer['name_pet'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_string($petCustomer['name_pet'])){
            throw new Exception(ConstMessage::ONLY_STRING,400);
        }

        if(!isset($petCustomer['customer_id'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_integer($petCustomer['customer_id'])){
            throw new Exception(ConstMessage::ONLY_INTEGER,400);
        }

        if(!isset($petCustomer['type_pet'])){
            throw new Exception(ConstMessage::FIELD_REQUIRED,404);
        }

        if(!is_integer($petCustomer['type_pet'])){
            throw new Exception(ConstMessage::ONLY_INTEGER,400);
        }

        return $petCustomer;
    }
}
