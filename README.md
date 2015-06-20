I18n module
===========
Yii2 internalization module

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist metalguardian/yii2-i18n-module "*"
```

or add

```
"metalguardian/yii2-i18n-module": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
'components' => [
    // ...
    'i18n' => [
        'translations' => [
            'messageSource' => [
                'class' => 'yii\i18n\PhpMessageSource',
                // ...
                'on missingTranslation' => ['metalguardian\components\TranslationEventHandler', 'handleMissingTranslation']
            ],
        ],
    ],
],
```
