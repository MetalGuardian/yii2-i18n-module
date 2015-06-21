<?php

namespace metalguardian\i18n;

use yii\base\InvalidConfigException;

/**
 * Class Module
 */
class Module extends \yii\base\Module
{
    public $languages;

    public function init()
    {
        parent::init();

        if ($this->languages instanceof \Closure) {
            $this->languages = call_user_func($this->languages);
        }

        if (!is_array($this->languages)) {
            throw new InvalidConfigException('\metalguardian\i18n\Module::languages have to be array.');
        }

        if (empty($this->languages)) {
            throw new InvalidConfigException('\metalguardian\i18n\Module::languages have to contains at least 1 item.');
        }
    }


    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'metalguardian\i18n\controllers';

    /**
     * Translate shortcut
     *
     * @param $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return \Yii::t('metalguardian/i18n', $message, $params, $language);
    }

    /**
     * Url to the translation controller
     *
     * @return array
     */
    public static function getUrl()
    {
        return ['/i18n/translation/index'];
    }
}
