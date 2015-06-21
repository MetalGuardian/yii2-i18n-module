<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m150101_000000_create_file_table
 */
class m150101_000000_create_file_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        $config = $i18n->getMessageSourceConfig();

        /** @var \yii\i18n\DbMessageSource $messageSource */
        $messageSource = Yii::createObject($config);

        if (!($messageSource instanceof \yii\i18n\DbMessageSource)) {
            throw new \yii\base\InvalidConfigException('I18n message source have to be instance of \yii\i18n\DbMessageSource');
        }

        $sourceMessageTable = $messageSource->sourceMessageTable;
        $messageTable = $messageSource->messageTable;

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING . '(32)',
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
        $config = $i18n->getMessageSourceConfig();

        /** @var \yii\i18n\DbMessageSource $messageSource */
        $messageSource = Yii::createObject($config);

        if (!($messageSource instanceof \yii\i18n\DbMessageSource)) {
            throw new \yii\base\InvalidConfigException('I18n message source have to be instance of \yii\i18n\DbMessageSource');
        }

        $sourceMessageTable = $messageSource->sourceMessageTable;
        $messageTable = $messageSource->messageTable;

        $this->dropTable($sourceMessageTable);
        $this->dropTable($messageTable);
    }
}
