<?php


namespace Webjump\WorldPetCustomer\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Webjump\WorldPet\Api\Data\PetKindInterface;
use Webjump\WorldPet\Model\PetKindRepository;

class Options extends AbstractSource implements SourceInterface, OptionSourceInterface
{

    private PetKindRepository $petKindRepository;

    public function __construct(PetKindRepository $petKindRepository)
    {
        $this->petKindRepository = $petKindRepository;
    }

    /**
     * @return array
     */
    public function getOptionArray(): array
    {
        $pets = $this->petKindRepository->getListPetKind();
        $returnArray = [];
        /** @var PetKindInterface $pet */
        foreach ($pets as $pet) {
            $returnArray[$pet->getPetKindEntityId()] = __($pet->getPetKindName());
        }
        return $returnArray;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        $result = [];
        $options = $this->getOptionArray();
        foreach ($options as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}
