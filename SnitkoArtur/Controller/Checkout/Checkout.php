<?php

namespace Amasty\SnitkoArtur\Controller\Checkout;

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

        $params = $this->_getParams();
        $quote = $this->_getQuote ();
        $product = $this->_getProduct($params["sku"]);
        $qty = $params["qty"];


        if ($this->_validateProduct($product, $qty)) {
            $this->_addInCart($quote, $product, $qty);
        }

        return $redirect->setPath("artur/form/form");
    }

    protected function _getParams () {
        return $this->getRequest()->getParams();
    }

    protected function _getQuote () {
        $quote = $this->session->getQuote();
        if(!$quote->getId()) {
            $quote->getId();
            $quote->save();
        }
        return $quote;
    }

    protected function _getProduct($param) {
        try {
            $product = $this->productRepository ->get($param);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $product = false;
        }

        return $product;
    }

    protected function _validateProduct ($product, $qty) {
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

    protected function _addInCart ($quote, $product, $qty) {
        $quote->addProduct($product, $qty);
        $quote->save();
        $this->messageManager->addSuccessMessage("Done!");
    }
}
