<?php

namespace Amasty\SnitkoArtur\Block\Email;

use Magento\Framework\View\Element\Template;

class Blacklist extends Template
{
    public function getProduct() {
        return $this->getData('blacklist_product');
    }
}
