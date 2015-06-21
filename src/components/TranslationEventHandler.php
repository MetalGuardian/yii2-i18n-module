<?php

namespace metalguardian\i18n\components;

use metalguardian\i18n\models\SourceMessage;
use Yii;
use yii\i18n\MissingTranslationEvent;

/**
 * Class TranslationEventHandler
 */
class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $driver = Yii::$app->getDb()->getDriverName();
        $condition = $driver === 'mysql' ? '= BINARY' : '=';
        $sourceMessage = SourceMessage::find()
            ->where(['category' => $event->category])
            ->andWhere([$condition, 'message', $event->message])
            ->one();
        if (!$sourceMessage) {
            SourceMessage::create($event->category, $event->message);
        }
    }
}
