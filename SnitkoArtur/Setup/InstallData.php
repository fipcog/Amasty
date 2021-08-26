<?php

namespace Amasty\SnitkoArtur\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $blacklistRows = [
            [
                'sku' => '24-MB01',
                'qty' => 8
            ],
            [
                'sku' => '24-MB02',
                'qty' => 10
            ],
            [
                'sku' => '24-MB03',
                'qty' => 11
            ]
        ];

        foreach ($blacklistRows as $data) {
            $setup->getConnection()->insert($setup->getTable(InstallSchema::TABLE_NAME), $data);
        }
    }
}
