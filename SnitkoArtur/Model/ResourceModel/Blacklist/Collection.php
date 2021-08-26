<?php

namespace Amasty\SnitkoArtur\Model\ResourceModel\Blacklist;

use Amasty\SnitkoArtur\Model\Blacklist;
use Amasty\SnitkoArtur\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Blacklist::class,
            BlacklistResource::class
        );
    }
}
