<?php

namespace metalguardian\i18n\components;

use metalguardian\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\i18n\DbMessageSource;

/**
 * Class I18n
 */
class I18n extends \yii\i18n\I18N
{
    /**
     * Configuration of the message sources
     *
     * @var array
     */
    public $messageSourceConfig = [];

    /**
     * Handle missing translations or not
     *
     * @var bool
     */
    public $handleMissing = true;

    /**
     * List of categories to handle by message source. If not an array category * will be used to handle all sources
     *
     * @var array
     */
    public $only = [
        'app',
        '*',
    ];

    /**
     * Override existing source messages or not
     *
     * @var bool
     */
    public $override = true;

    /**
     * List of supported languages
     *
     * @var
     */
    public $languages;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->languages instanceof \Closure) {
            $this->languages = call_user_func($this->languages);
        }

        if (!is_array($this->languages)) {
            throw new InvalidConfigException('\metalguardian\i18n\components\I18n::languages have to be array.');
        }

        if (empty($this->languages)) {
            throw new InvalidConfigException('\metalguardian\i18n\components\I18n::languages have to contains at least 1 item.');
        }

        $config = $this->getMessageSourceConfig();

        if (is_array($this->only)) {
            foreach ($this->only as $item) {
                if ($this->override || (!isset($this->translations[$item]) && !isset($this->translations[$item . '*']))) {
                    $this->translations[$item] = $config;
                }
            }
        } else {
            if ($this->override) {
                $this->translations = [
                    '*' => $config,
                ];
            }
        }
    }

    /**
     * @return array
     */
    protected function getMessageSourceConfig()
    {
        $defaults = [
            'class' => DbMessageSource::className(),
        ];
        if ($this->handleMissing) {
            $defaults['on missingTranslation'] = [
                'metalguardian\i18n\components\TranslationEventHandler',
                'handleMissingTranslation',
            ];
        }

        return ArrayHelper::merge($defaults, $this->messageSourceConfig);
    }

    public function getSourceMessageTable()
    {
        $config = $this->getMessageSourceConfig();

        /** @var \yii\i18n\DbMessageSource $messageSource */
        $messageSource = Yii::createObject($config);

        if (!($messageSource instanceof \yii\i18n\DbMessageSource)) {
            throw new \yii\base\InvalidConfigException(Module::t('I18n message source have to be instance of \yii\i18n\DbMessageSource'));
        }

        return $messageSource->sourceMessageTable;
    }

    public function getMessageTable()
    {
        $config = $this->getMessageSourceConfig();

        /** @var \yii\i18n\DbMessageSource $messageSource */
        $messageSource = Yii::createObject($config);

        if (!($messageSource instanceof \yii\i18n\DbMessageSource)) {
            throw new \yii\base\InvalidConfigException(Module::t('I18n message source have to be instance of \yii\i18n\DbMessageSource'));
        }

        return $messageSource->messageTable;
    }
}
