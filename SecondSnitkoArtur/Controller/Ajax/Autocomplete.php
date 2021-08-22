<?php

namespace Amasty\SecondSnitkoArtur\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Autocomplete extends Action
{
    protected $productCollectionFactory;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CollectionFactory $CollectionFactory,
        JsonFactory $JsonFactory
    )
    {
        parent::__construct($context);
        $this->productCollectionFactory = $CollectionFactory;
        $this->resultJsonFactory = $JsonFactory;
    }

    public function execute()
    {
        $response = [];
        $result = $this->resultJsonFactory->create();

        $queryText = $this->getQueryText();
        $collection = $this->getCollectionWithExtraAttr();

        foreach ($collection as $product) {
            $sku = $product->getSku();
            if(strpos($sku, $queryText) !== false) {
                $name = $product->getName();
                array_push($response, ["sku" => $sku, "name" => $name]);
            }
        }

        return $result->setData($response);
    }

    protected function getQueryText () {
        $params = $this->getRequest()->getParams();
        return $params['qText'];
    }

    protected function getCollectionWithExtraAttr () {
        return $this->productCollectionFactory->create()->addAttributeToSelect('*');
    }
}
