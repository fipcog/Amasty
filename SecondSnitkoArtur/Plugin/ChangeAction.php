<?php

namespace Amasty\SecondSnitkoArtur\Plugin;

use Magento\Framework\UrlInterface;

class ChangeAction
{
    /**
     * @var UrlInterface
     */
    public $urlBuilder;

    public function __construct
    (
        UrlInterface $urlInterface
    )
    {
        $this->urlBuilder = $urlInterface;
    }

    public function afterGetFormAction ($subject, $result) {
        return $this->urlBuilder->getUrl('checkout/cart/add');
    }
}
