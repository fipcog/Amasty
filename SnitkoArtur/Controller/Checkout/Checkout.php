<?php

namespace Amasty\SnitkoArtur\Controller\Checkout;

use Amasty\SnitkoArtur\Model\Blacklist;
use Amasty\SnitkoArtur\Model\BlacklistFactory;
use Amasty\SnitkoArtur\Model\BlacklistRepository;
use Amasty\SnitkoArtur\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;


class Checkout extends Action
{
    /**
     * @var BlacklistFactory
     */
    protected $blacklistFactory;

    /**
     * @var BlacklistResource
     */
    protected $blacklistResource;

    /**
     * @var BlacklistRepository
     */
    protected $blacklistRepository;

    /**
     * @var CheckoutSession
     */
    protected $session;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var EventManager
     */
    protected $eventManager;

    public function __construct(
        Context $context,
        CheckoutSession $session,
        ProductRepositoryInterface $productRepository,
        EventManager $eventManager,
        BlacklistResource $blacklistResource,
        BlacklistFactory $blacklistFactory,
        BlacklistRepository $blacklistRepository
    )
    {
        parent::__construct($context);
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
        $this->blacklistResource = $blacklistResource;
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();

        $params = $this->getParams();
        $quote = $this->getQuote();
        $product = $this->getProduct($params['sku']);
        $qty = $params["qty"];

        if ($this->validateProduct($product, $qty)) {
            /**
             * @var \Amasty\SnitkoArtur\Model\Blacklist $blacklistProduct
             */
            $blacklistProduct = $this->findInBlacklistBySku($params['sku']);
            $inCartQty = $this->getInCartQty($quote, $params['sku']);
            $sumOfOrderedQty = $inCartQty + $qty;
            $maxQty = $this->getMaxQty($blacklistProduct);

            if(!$blacklistProduct) {
                $this->addInCart($quote, $product, $qty);

            } elseif ($maxQty === $inCartQty) {
                $this->messageManager->addErrorMessage("You can't buy more then " . $maxQty . " items!");

            } elseif ($maxQty > $inCartQty && $maxQty < $sumOfOrderedQty) {
                $availableQty = $maxQty - $inCartQty;

                $this->addInCart($quote, $product, $availableQty);
                $this->messageManager->addWarningMessage("You can't buy more then " . $maxQty . " items!
                 Only " . $availableQty . " items was added.");

            } else {
                $this->addInCart($quote, $product, $qty);
            }
        }

//        $this->eventManager->dispatch(
//            'amasty_snitkoartur_add_promo',
//            ['request' => $this->getRequest()]
//        );

        return $redirect->setPath("artur/form/form");
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

    protected function findInBlacklistBySku ($sku) {
        try {
            $blacklistItem = $this->blacklistRepository->getBySku($sku);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e){
            $blacklistItem = false;
        }

        return $blacklistItem;
    }

    protected function getInCartQty ($quote, $sku) {
        $itemsInCart = $quote->getAllVisibleItems();
        $inCartQty = 0;

        foreach ($itemsInCart as $itemID => $item) {
            if($item->getSku() === $sku) {
                $inCartQty = $item->getQty();
            }
        }

        return (int)$inCartQty;
    }

    public function getMaxQty ($product) {
        /**
         * @var \Amasty\SnitkoArtur\Model\Blacklist $product
         */
        return $product->getQty();
    }
}
