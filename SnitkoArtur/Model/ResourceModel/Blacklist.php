<?php

namespace Amasty\SnitkoArtur\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\SnitkoArtur\Setup\InstallSchema::TABLE_NAME,
            'blacklist_product_id'
        );
    }
}
