<?php

namespace metalguardian\i18n;

use metalguardian\i18n\console\MessageController;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package metalguardian\i18n
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            if (!isset($app->controllerMap['message'])) {
                $app->controllerMap['message'] = MessageController::className();
            }
        }
    }
}
