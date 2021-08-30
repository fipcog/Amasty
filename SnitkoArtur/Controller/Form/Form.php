<?php

namespace Amasty\SnitkoArtur\Controller\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;


class Form implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if($this->scopeConfig->isSetFlag('artur_module_config/general/module_enabled')) {
            return $this->resultPageFactory->create();
        } else {
            die('Module is disabled');
        }
    }
}
