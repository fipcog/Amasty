<?php

namespace Amasty\SnitkoArtur\Model;

use \Magento\Framework\Model\AbstractModel;

/**
 * Class Blacklist
 *
 * @method string getSku()
 * @method int getQty()
 */
class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            ResourceModel\Blacklist::class
        );
    }
}
