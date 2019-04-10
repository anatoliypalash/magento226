<?php

namespace PalashAs\AskQuestion\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use PalashAs\AskQuestion\Model\AskQuestion;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            /**
             * Create table 'palashas_askquestion'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('palashas_askquestion')
            )->addColumn(
                'question_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Question ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name'
            )->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Email'
            )->addColumn(
                'phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                63,
                [],
                'Phone Number'
            )->addColumn(
                'product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                127,
                ['nullable' => false],
                'Product Name'
            )->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                63,
                ['nullable' => false],
                'Sku'
            )->addColumn(
                'question',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Question'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                15,
                ['nullable' => false, 'default' => AskQuestion::STATUS_PENDING],
                'Status'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )->addForeignKey(
                $installer->getFkName(
                    $installer->getTable('palashas_askquestion'),
                    'store_id',
                    'store',
                    'store_id'
                ),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'Ask Question'
            );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }

}
