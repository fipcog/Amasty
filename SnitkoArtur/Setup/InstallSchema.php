<?php

namespace Amasty\SnitkoArtur\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use phpseclib\System\SSH\Agent\Identity;

class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'amasty_snitkoartur_blacklist';

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable(self::TABLE_NAME))
            ->addColumn(
                'blacklist_product_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,     //уникальность
                    'unsigned' => true,     //только положительные
                    'nullable' => false,    //не может быть null
                    'primary' => true       //основная колонка
                ],
                'Id of blacklist product'
            )
            ->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Sku of product in blacklist'
            )
            ->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => false,
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => 10
                ],
                'Available quantity of product for single person'
            )
            ->setComment('Table of product which quantity is limited for single customer');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
