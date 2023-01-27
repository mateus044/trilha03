<?php

namespace Webjump\WorldPet\Model\Config\Source;

use Webjump\WorldPet\Model\ResourceModel\PetKind\CollectionFactory;
class SelectPet
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;


    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        return [];
    }
}
