<?php

namespace Amasty\SnitkoArtur\UI;

use Magento\Framework\App\Config\ScopeConfigInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

    public $pathPrefix;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    abstract public function getValue ($path, $scope);
}
