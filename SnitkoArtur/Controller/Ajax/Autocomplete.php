<?php

namespace Amasty\SnitkoArtur\Controller\Ajax;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;



class Autocomplete extends Action
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var JsonFactory
     */
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
        $productCollection = $this->getProductCollectionBySku($queryText);

        foreach ($productCollection as $product) {
            $sku = $product->getSku();
            $name = $product->getName();
            array_push($response, ["sku" => $sku, "name" => $name]);
        }

        return $result->setData($response);
    }

    protected function getQueryText () {
        $params = $this->getRequest()->getParams();
        return $params['qText'];
    }

    protected function getProductCollectionBySku ($sku) {
        return $this->productCollectionFactory->create()
                                                ->addAttributeToSelect(['name'])
                                                ->addAttributeToFilter('sku', ['like' => $sku . '%'], ['like' => '%' . $sku])
                                                ->setPageSize(20);
    }
}
