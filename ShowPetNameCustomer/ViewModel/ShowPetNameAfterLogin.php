<?php

namespace Webjump\ShowPetNameCustomer\ViewModel;

use Magento\Customer\Api\Data\CustomerInterface;
use Webjump\WorldPetCustomer\Model\Config;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Exception\LocalizedException;
use Webjump\WorldPet\Api\Data\PetKindInterface;
use Webjump\WorldPet\Model\PetKindManager;


class ShowPetNameAfterLogin implements ArgumentInterface
{
    /**
     * @const ATTRIBUTE_CODE
     */
    const ATTRIBUTE_CODE = 'pet_kind';

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var PetKindInterface
     */
    private PetKindInterface $petKindInterface;

    /**
     * @var PetKindManager
     */
    private PetKindManager $petKindManager;


    /**
     * @param Session $session
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param PetKindInterface $petKindInterface
     * @param PetKindManager $petKindManager
     */
    public function __construct(
        Session $session,
        Config $config,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        PetKindInterface $petKindInterface,
        PetKindManager $petKindManager
    ) {
        $this->session = $session;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->petKindInterface = $petKindInterface;
        $this->petKindManager = $petKindManager;
    }

    /**
     * Method to check if customer is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->session->isLoggedIn();
    }

    /**
     * Method to return module status
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isModuleEnable(): bool
    {
        $storeCode = $this->storeManager->getStore()->getCode();
        return $this->config->isEnabled($storeCode);
    }

    /**
     * Method to return current customer in session
     *
     * @return CustomerInterface|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomer(): ?CustomerInterface
    {
        $customerId = $this->session->getCustomerId();
        return $this->customerRepository->getById($customerId);
    }

    /**
     * Method to return the pet name of the customer
     *
     * @return string|null
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getPetNameCustomer(): ?string
    {
        return $this->getCustomer()->getExtensionAttributes()->getPetName();
    }

    /**
     * Method to return the pet kind of the customer
     *
     * @return string|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getPetKindCustomer(): ?string
    {
        $petKindId = $this->getCustomer()->getCustomAttribute(self::ATTRIBUTE_CODE);
        if(is_null($petKindId)) {
            return null;
        }
        $this->petKindInterface->setPetKindEntityId((int) $petKindId->getValue());
        $response = $this->petKindManager->managePetSearch($this->petKindInterface);
        if(!isset($response['name']))
        {
            return null;
        }
        return $response['name'];
    }
}
