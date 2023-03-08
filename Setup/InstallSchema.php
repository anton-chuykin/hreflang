<?php

namespace Kg\Hreflang\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * Install schema
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists(SchemaHelperInterface::TABLE_CMS_RELATIONSHIP)) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable(SchemaHelperInterface::TABLE_CMS_RELATIONSHIP)
            )
                ->addColumn(
                    'page_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [
                        'nullable' => false,
                        'primary' => true,
                    ],
                    'Page Id'
                )
                ->addColumn(
                    'parent_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Parent Id'
                )->addForeignKey(
                    $installer->getFkName(
                        SchemaHelperInterface::TABLE_CMS_RELATIONSHIP,
                        'page_id',
                        SchemaHelperInterface::TABLE_CMS_PAGE,
                        'page_id'
                    ),
                    'page_id',
                    $installer->getTable(SchemaHelperInterface::TABLE_CMS_PAGE),
                    'page_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );

            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
