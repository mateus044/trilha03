<?php

namespace Webjump\WorldPetCustomer\Helper\Response;

class PatterResponse
{
    /**
     * @param $entity
     * @return array
     */
    public function responseGetPetCustomer($entity)
    {
        $entity = $entity->getData();
        $petCustomer = [
            'id' => $entity['entity_id'],
            'name' => $entity['name_pet'],
            'type' => $entity['type_pet'],
            'customer' => $entity['customer_id']
        ];
        return $petCustomer;
    }


    public function mountPets($pets)
    {
        $response = [];
        foreach ($pets as $pet) {
            $response[] = array(
                'id' => $pet->getData('entity_id'),
                'name' => $pet->getData('name_pet'),
                'type' => $pet->getData('type_pet'),
                'customer' => $pet->getData('customer_id')
            );
        }
        return $response;
    }
}
