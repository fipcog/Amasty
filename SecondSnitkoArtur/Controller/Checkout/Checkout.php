<?php

namespace Amasty\SecondSnitkoArtur\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Checkout extends Action
{
    /**
     * @var CheckoutSession
     */
    protected $session;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        Context $context,
        CheckoutSession $session,
        ProductRepositoryInterface $productRepository
    )
    {
        parent::__construct($context);
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();

        $params = $this->getParams();
        $quote = $this->getQuote ();
        $product = $this->getProduct($params['sku']);
        $qty = $params["qty"];


        if ($this->validateProduct($product, $qty)) {
            $this->addInCart($quote, $product, $qty);
        }

        return $redirect->setPath("secondartur/form/form");
    }

    protected function getParams () {
        return $this->getRequest()->getParams();
    }

    protected function getQuote () {
        $quote = $this->session->getQuote();
        if(!$quote->getId()) {
            $quote->getId();
            $quote->save();
        }
        return $quote;
    }

    protected function getProduct($param) {
        try {
            $product = $this->productRepository ->get($param);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $product = false;
        }

        return $product;
    }

    protected function validateProduct ($product, $qty) {
        if(!$product) {
            $this->messageManager->addWarningMessage("This product does not exist");
        } elseif ($product->getTypeId() !== "simple") {
            $this->messageManager->addWarningMessage("Wrong product type");
        } elseif ($qty > $product->getExtensionAttributes()->getStockItem()->getQty()) {
            $this->messageManager->addWarningMessage("We don't have that many items");
        } else {
            return true;
        }
    }

    protected function addInCart ($quote, $product, $qty) {
        $quote->addProduct($product, $qty);
        $quote->save();
        $this->messageManager->addSuccessMessage("Done!");
    }
}
