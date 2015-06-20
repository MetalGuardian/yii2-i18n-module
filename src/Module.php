<?php

namespace metalguardian\i18n;

/**
 * Class Module
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{

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
     * @inheritdoc
     */
    public function bootstrap($app)
    {

    }
}
