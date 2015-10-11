<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150101_000000_create_i18n_tables
 */
class m150101_000000_create_i18n_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        $sourceMessageTable = $i18n->getSourceMessageTable();
        $messageTable = $i18n->getMessageTable();

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING,
            'message' => Schema::TYPE_TEXT
        ], $tableOptions);

        $this->createTable($messageTable, [
            'id' => Schema::TYPE_INTEGER,
            'language' => Schema::TYPE_STRING . '(16)',
            'translation' => Schema::TYPE_TEXT
        ], $tableOptions);

        $this->addPrimaryKey('', $messageTable, ['id', 'language']);
        $this->addForeignKey('fk_message_source_message', $messageTable, 'id', $sourceMessageTable, 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        $sourceMessageTable = $i18n->getSourceMessageTable();
        $messageTable = $i18n->getMessageTable();

        $this->dropTable($messageTable);
        $this->dropTable($sourceMessageTable);
    }
}
