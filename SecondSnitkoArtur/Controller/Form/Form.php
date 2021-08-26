<?php

namespace Amasty\SecondSnitkoArtur\Controller\Form;

use Amasty\SnitkoArtur\Controller\Form\Form as BasicForm;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;

class Form extends BasicForm
{
    /**
     * @var Session
     */
    protected $customerSession;

    public function __construct
    (
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig,
        Session $customerSession
    )
    {
        parent::__construct($resultPageFactory, $scopeConfig);
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        if($this->customerSession->isLoggedIn()) {
            return parent::execute();
        } else {
            die('You are not logged in');
        }
    }
}
