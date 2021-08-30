<?php

namespace Amasty\SnitkoArtur\Model;

use Amasty\SnitkoArtur\Model\Blacklist;
use Amasty\SnitkoArtur\Model\BlacklistFactory;
use Amasty\SnitkoArtur\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository
{
    protected $blacklistFactory;

    protected $blacklistResource;

    public function __construct
    (
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource
    )
    {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
    }

    public function getBySku ($sku) {
        $blacklist = $this->blacklistFactory->create();
        $this->blacklistResource->load($blacklist, $sku, 'sku');

        return $blacklist;
    }

    public function setDataInProduct ($sku, $colName, $data) {
        $product = $this->getBySku($sku);
        $product->setData($colName, $data);
        $this->blacklistResource->save($product);
    }
}
