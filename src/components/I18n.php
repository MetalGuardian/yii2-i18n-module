<?php

namespace metalguardian\i18n\components;

use yii\helpers\ArrayHelper;
use yii\i18n\DbMessageSource;

/**
 * Class I18n
 */
class I18n extends \yii\i18n\I18N
{
    public $messageSourceConfig = [];

    public function init()
    {
        parent::init();

        $defaults = [
            'class' => DbMessageSource::className(),
            'on missingTranslation' => ['metalguardian\i18n\TranslationEventHandler', 'handleMissingTranslation'],
        ];
        $config = ArrayHelper::merge($defaults, $this->messageSourceConfig);

        $this->translations['*'] = $config;
    }

}
