<?php

namespace metalguardian\i18n\components;

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
     * List of categories to handle by message source. If not an array category * will be used to handle all sources
     *
     * @var array
     */
    public $only = [
        'app',
        '*',
    ];

    public $override = false;

    public function init()
    {
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

        parent::init();
    }

    /**
     * @return array
     */
    public function getMessageSourceConfig()
    {
        $defaults = [
            'class' => DbMessageSource::className(),
            'on missingTranslation' => [
                'metalguardian\i18n\components\TranslationEventHandler',
                'handleMissingTranslation'
            ],
        ];

        return ArrayHelper::merge($defaults, $this->messageSourceConfig);
    }

}
