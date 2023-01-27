<?php

namespace Webjump\WorldPetCustomer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @const PET_KIND_PATH
     */
    const PET_KIND_PATH = 'pets_section/pets_group/pets_field_enable';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Constructor to Config class
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(?string $scopeCode): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::PET_KIND_PATH,
            ScopeInterface::SCOPE_STORE,
            $scopeCode
        );
    }
}
