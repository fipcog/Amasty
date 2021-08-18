<?php

namespace Amasty\SnitkoArtur\Model;

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

    public function getValue ($path, $scope = "store") {
        return $this->scopeConfig->getValue($this->pathPrefix.$path);
    }
}
