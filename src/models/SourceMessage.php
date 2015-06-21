<?php

namespace metalguardian\i18n\models;

use metalguardian\i18n\components\I18n;
use metalguardian\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "{{%source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        if (!($i18n instanceof I18n)) {
            throw new InvalidConfigException(Module::t('I18n component have to be instance of metalguardian\i18n\components\I18n'));
        }

        return $i18n->getSourceMessageTable();
    }

    /**
     * Create source message
     *
     * @param $category
     * @param $message
     */
    public static function create($category, $message)
    {
        $sourceMessage = new SourceMessage;
        $sourceMessage->category = $category;
        $sourceMessage->message = $message;
        $sourceMessage->save(false);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'category' => Module::t('Category'),
            'message' => Module::t('Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
    }

    /**
     * @inheritdoc
     * @return SourceMessageQuery
     */
    public static function find()
    {
        return new SourceMessageQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getCategories()
    {
        return SourceMessage::find()->select('category')->distinct('category')->asArray()->all();
    }

    /**
     * Populate messages
     *
     * @throws InvalidConfigException
     */
    public function populateMessages()
    {
        /** @var Module $module */
        $module = Module::getInstance();
        if (!$module) {
            throw new InvalidConfigException(Module::t('You need to configure \metalguardian\i18n\Module'));
        }
        $messages = [];
        foreach ($module->languages as $language) {
            if (!isset($this->messages[$language])) {
                $message = new Message();
                $message->language = $language;
                $messages[$language] = $message;
            } else {
                $messages[$language] = $this->messages[$language];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    /**
     * Link messages
     */
    public function linkMessages()
    {
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
        }
    }
}
