<?php

namespace Amasty\SecondSnitkoArtur\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Form extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    public function isQtyEnabled () {
        return $this->scopeConfig->isSetFlag('artur_module_config/general/show_quantity');
    }

    public function getQtyNumber () {
        return $this->scopeConfig->getValue('artur_module_config/general/quantity');
    }
}
