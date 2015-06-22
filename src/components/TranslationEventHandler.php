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
    /**
     * Handle missing translations
     *
     * @param MissingTranslationEvent $event
     */
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $sourceMessage = SourceMessage::get($event->category, $event->message);
        if (!$sourceMessage) {
            SourceMessage::create($event->category, $event->message);
        }
    }
}
