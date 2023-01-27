<?php

namespace Webjump\ShowPetNameCustomer\Plugin\Magento\Customer\Model\ResourceModel;

use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;


class CustomerRepositoryPlugin
{

    /**
     * @const ATTRIBUTE_CODE
     */
    const ATTRIBUTE_CODE = 'pets';

    /**
     * @var CustomerInterface
     */
    private CustomerInterface $customer;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * Constructor to CustomerRepositoryPlugin class
     *
     * @param CustomerInterface $customer
     * @param CustomerRepositoryInterface $customerRepository
     * @param RequestInterface $request
     */
    public function __construct(
        CustomerInterface $customer,
        CustomerRepositoryInterface $customerRepository,
        RequestInterface $request
    ) {
        $this->customer = $customer;
        $this->customerRepository = $customerRepository;
        $this->request = $request;
    }

    /**
     * Method to return configured extension attribute to customer
     *
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function setPetNameAttributeName(CustomerInterface $customer): CustomerInterface
    {
        $petName = $customer->getCustomAttribute(self::ATTRIBUTE_CODE);
        if (!$petName) {
            return $customer;
        }

        $extensionAttributes = $customer->getExtensionAttributes();
        $extensionAttributes->setPetName($petName->getValue());
        $customer->setExtensionAttributes($extensionAttributes);
        return $customer;
    }

    /**
     * After plugin in the Repository get method
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterGet(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result
    ): CustomerInterface {
        return $this->setPetNameAttributeName($result);
    }

    /**
     * After plugin in the Repository getById method
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @param int $customerId
     * @return CustomerInterface
     */
    public function afterGetById(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result,
        int $customerId
    ): CustomerInterface {
        return $this->setPetNameAttributeName($result);
    }

    /**
     * After plugin in the Repository getList method
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerSearchResultsInterface $results
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomerSearchResultsInterface
     */
    public function afterGetList(
        CustomerRepositoryInterface $subject,
        CustomerSearchResultsInterface $results,
        SearchCriteriaInterface $searchCriteria
    ): CustomerSearchResultsInterface {
        $customers = [];

        foreach ($results->getItems() as $entity) {
            $customer = $this->setPetNameAttributeName($entity);
            $customers[] = $customer;
        }

        $results->setItems($customers);
        return $results;
    }
}
