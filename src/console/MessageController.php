<?php

namespace metalguardian\i18n\console;

use metalguardian\i18n\components\I18n;
use metalguardian\i18n\models\Message;
use metalguardian\i18n\models\SourceMessage;
use metalguardian\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * Class MessageController
 */
class MessageController extends \yii\console\controllers\MessageController
{
    /**
     * @param string $sourcePath root path to scan translation files
     * @param bool $override
     * @throws Exception
     */
    public function actionImport($sourcePath, $override = false)
    {
        $sourcePath = realpath(Yii::getAlias($sourcePath));
        if (!is_dir($sourcePath)) {
            throw new Exception('The source path [[' . $sourcePath . ']] is not a valid directory.');
        }
        $files = FileHelper::findFiles($sourcePath, ['only' => ['*.php']]);
        foreach ($files as $fileName) {
            $relativePath = trim(str_replace([$sourcePath, '.php'], '', $fileName), '/,\\');
            $relativePath = FileHelper::normalizePath($relativePath, '/');
            $relativePath = explode('/', $relativePath, 2);
            if (count($relativePath) > 1) {
                $this->stdout("Processing file [[{$fileName}]]\n");
                $language = $this->prompt('Chose language: ', ['default' => $relativePath[0]]);
                $category = $this->prompt('Chose category: ', ['default' => $relativePath[1]]);
                $this->actionImportFile($fileName, $category, $language, $override);
            }
        }
        $this->stdout("Done\n\n");
    }

    /**
     * @param $fileName
     * @param $category
     * @param $language
     * @param $override
     */
    public function actionImportFile($fileName, $category, $language, $override = false)
    {
        $translations = require($fileName);
        $i = 0;
        $saved = 0;
        $notSaved = 0;
        $skipped = 0;
        if (is_array($translations)) {
            foreach ($translations as $sourceMessage => $translation) {
                if (!empty($translation)) {
                    $sourceMessage = $this->getSourceMessage($category, $sourceMessage);
                    $result = $this->setTranslation($sourceMessage, $language, $translation, $override);
                    if ($result === true) {
                        $saved++;
                    } elseif ($result === false) {
                        $notSaved++;
                    } elseif ($result === null) {
                        $skipped++;
                    }
                } else {
                    $skipped++;
                }
                $i++;
            }
        }
        $this->stdout("Translation [[{$category}]].[[{$language}]]: ");
        $this->stdout("updated {$saved} ", Console::FG_GREEN);
        $this->stdout("from {$i}; ");
        if ($skipped) {
            $this->stdout("{$skipped} skipped; ", Console::FG_YELLOW);
        }
        if ($notSaved) {
            $this->stdout("{$notSaved} errors; ", Console::FG_RED);
        }
        $this->stdout("\n\n");
    }

    /**
     * @param string $messagePath
     * @param string $category
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionExport($messagePath, $category = null)
    {
        $messagePath = realpath(Yii::getAlias($messagePath));
        if (!is_dir($messagePath)) {
            throw new Exception('The message path [[' . $messagePath . ']] is not a valid directory.');
        }
        /** @var SourceMessage[] $sourceMessages */
        $sourceMessages = SourceMessage::find()
            ->filterWhere(['category' => $category])
            ->with(['messages'])
            ->asArray()
            ->all();
        $messages = [];

        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        if (!($i18n instanceof I18n)) {
            throw new InvalidConfigException(Module::t('I18n component have to be instance of metalguardian\i18n\components\I18n'));
        }
        foreach ($sourceMessages as $sourceMessage) {
            $translations = $sourceMessage['messages'];
            foreach ($i18n->languages as $language) {
                $messages[$sourceMessage['category']][$language][$sourceMessage['message']] = isset($translations[$language]) && !empty($translations[$language]['translation']) ? $translations[$language]['translation'] : '';
            }
        }

        foreach ($messages as $category => $languages) {
            foreach ($languages as $language => $translations) {
                $fileName = FileHelper::normalizePath($messagePath . '/' . $language . '/' . $category) . '.php';

                if (!is_file($fileName)) {
                    $dir = dirname($fileName);
                    if (!FileHelper::createDirectory($dir)) {
                        throw new Exception('Directory [[' . $dir . ']] is not created');
                    }
                }

                ksort($translations);
                $array = VarDumper::export($translations);
                $content = <<<EOD
<?php
/**
 * Message translations.
 *
 * This file is automatically exported from database
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return {$array};

EOD;

                file_put_contents($fileName, $content);
                $this->stdout("Translation for category [[{$category}]] for language [[{$language}]] exported to the [[{$fileName}]].\n\n", Console::FG_GREEN);
            }
        }
    }

    /**
     * @param string $category
     * @param string $message
     * @return SourceMessage
     */
    protected function getSourceMessage($category, $message)
    {
        $sourceMessage = SourceMessage::get($category, $message);
        if (!$sourceMessage) {
            $sourceMessage = SourceMessage::create($category, $message);
        }
        return $sourceMessage;
    }

    /**
     * @param SourceMessage $sourceMessage
     * @param string $language
     * @param string $translation
     * @param $override
     * @return bool|null
     */
    protected function setTranslation($sourceMessage, $language, $translation, $override)
    {
        /** @var Message[] $messages */
        $messages = $sourceMessage->messages;
        if (isset($messages[$language]) && ($override || $messages[$language]->translation === null)) {
            $messages[$language]->translation = $translation;
            return $messages[$language]->save();
        } elseif (!isset($messages[$language])) {
            $message = new Message();
            $message->language = $language;
            $message->translation = $translation;
            $message->id = $sourceMessage->id;
            return $message->save();
        }

        return null;
    }
}
