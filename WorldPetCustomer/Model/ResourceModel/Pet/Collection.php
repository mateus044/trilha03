<?php

namespace Webjump\WorldPetCustomer\Model\ResourceModel\Pet;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Webjump\WorldPetCustomer\Model\Pet;
use Webjump\WorldPetCustomer\Model\ResourceModel\Pet as PetResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Pet::class, PetResourceModel::class);
    }
}
