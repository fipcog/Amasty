<?php

namespace Amasty\SnitkoArtur\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;
use phpDocumentor\Reflection\Types\Context;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if(version_compare($context->getVersion(), '0.0.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable(InstallSchema::TABLE_NAME),
                'Test',
                [
                    'type' => Table::TYPE_TEXT,
                    'size' => 50,
                    'comment' => 'Column for testing upgrade schema'
                ]
            );
        }

        $setup->endSetup();
    }
}