<?php

namespace Amasty\SecondSnitkoArtur\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;

class AddDataToRequest
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;


    protected $messageManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    )
    {
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function beforeExecute ($subject) {
        $params = $subject->getRequest()->getParams();

        if(!isset($params['product']) && isset($params['sku'])) {
            $productId = $this->getProductId($params['sku']);

            if($productId) {
                $subject->getRequest()->setParam('product', $productId);
            } else {
                $this->messageManager->addErrorMessage('The "sku" is incorrect');
                exit;
            }
        }
    }

    public function getProductId ($sku) {
        try {
            $product = $this->productRepository->get($sku);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            return false;
        }

        return $product->getId();
    }

}
