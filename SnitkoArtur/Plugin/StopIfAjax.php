<?php

namespace Amasty\SnitkoArtur\Plugin;

class StopIfAjax
{
    public function beforeExecute ($subject, $observer) {
        if($observer->getData('request')->isAjax()) exit;
        return [$observer];
    }
}
