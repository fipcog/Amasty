<?php

namespace Amasty\SnitkoArtur\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider extends ConfigProviderAbstract
{
    public $pathPrefix = "artur_module_config/";

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        parent::__construct($scopeConfig);
    }

    public function getGreetingText () {
        $this->getValue("general/greeting_text");
    }

    public function isQtyShowed () {
        $this->getValue("general/show_quantity");
    }

    public function getQuantity () {
        $this->getValue("general/quantity");
    }
}
