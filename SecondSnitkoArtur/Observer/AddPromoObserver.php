<?php

namespace Amasty\SecondSnitkoArtur\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;

class AddPromoObserver implements ObserverInterface
{
    protected $session;

    protected $productRepository;

    protected $scopeConfig;

    public function __construct
    (
        CheckoutSession $checkoutSession,
        ProductRepository $productRepository,
        ScopeConfig $scopeConfig
    )
    {
        $this->session = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $request = $observer->getData('request');
        $querySku = $request->getParam('sku');
        $promoProduct = $this->getPromoProduct();
        $availableSkuArr = $this->getAvailableSkuArr();
        $quote = $this->getQuote();

        foreach ($availableSkuArr as $value) {
            if($querySku === $value) {
                $this->addInCart($quote, $promoProduct);
            }
        }
    }

    protected function addInCart ($quote, $product) {
        $quote->addProduct($product, 1);
        $quote->save();
    }

    protected function getAvailableSkuArr () {
        $availableSkuString = $this->scopeConfig->getValue('artur_second_module_config/general/for_sku');
        return explode(', ', $availableSkuString);
    }

    protected function getPromoProduct () {
        $promoSku = $this->scopeConfig->getValue('artur_second_module_config/general/promo_sku');
        return $this->productRepository->get($promoSku);
    }

    protected function getQuote () {
        $quote = $this->session->getQuote();
        if(!$quote->getId()) {
            $quote->getId();
            $quote->save();
        }
        return $quote;
    }
}
