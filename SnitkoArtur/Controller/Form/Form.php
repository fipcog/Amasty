<?php

namespace Amasty\SnitkoArtur\Controller\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Form implements HttpGetActionInterface
{
    protected $resultPageFactory;

    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
