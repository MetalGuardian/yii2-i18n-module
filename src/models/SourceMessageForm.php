<?php

namespace metalguardian\i18n\models;

use metalguardian\i18n\components\I18n;
use metalguardian\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Form used to create source message items
 */
class SourceMessageForm extends Model
{
    public $message;
    public $category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message'], 'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => Module::t('Category'),
            'message' => Module::t('Message'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        SourceMessage::create($this->category, $this->message);

        return true;
    }
}
