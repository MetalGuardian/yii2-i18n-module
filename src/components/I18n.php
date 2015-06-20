<?php

namespace metalguardian\i18n\components;

use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;

/**
 * Class I18n
 */
class I18n extends \yii\i18n\I18N
{
    /** @var array */
    public $languages;
    /** @var array */
    public $missingTranslationHandler = ['metalguardian\i18n\TranslationEventHandler', 'handleMissingTranslation'];
    public $messageSource = [
        'class' => DbMessageSource::class,
    ];

    public function init()
    {
        parent::init();

        if ($this->languages instanceof \Closure) {
            $this->languages = call_user_func($this->languages);
        }

        if (!is_array($this->languages)) {
            throw new InvalidConfigException('I18n::languages have to be array.');
        }

        if (empty($this->languages)) {
            throw new InvalidConfigException('I18n::languages have to contains at least 1 item.');
        }

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }
        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => DbMessageSource::className(),
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }

    }

}
