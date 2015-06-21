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

How to configure component:

```php
'components' => [
    // ...
    'i18n' => [
        'class' => '\metalguardian\i18n\components\I18n',
        // you can configure message sources, which will be used to handle all sources
        'messageSourceConfig' => [
            // here you can change all message source settings
            //'class' => \yii\i18n\DbMessageSource::className(),
        ],
        // you can turn off handling missing translations
        'handleMissing' => true,
        // here you can setup which categories to handle
        'only' => [
            'app',
            '*',
            // for example, you can add 'yii' core category
            //'yii',
        ],
        // do you want to override already configured message sources?
        'override' => true,
    ],
],
```

You can handle ALL message sources like this:

```php
'components' => [
    // ...
    'i18n' => [
        'class' => '\metalguardian\i18n\components\I18n',
        'only' => false,
        'override' => true,
    ],
],
```

This will override all other message sources and setup '*' source, which handle all translations in application

If you don't have message tables in your database yet, you may use migrations:

```php
./yii migrate --migrationPath=@vendor/metalguardian/yii2-i18n-module/src/migrations
```

In admin application you need to configure translation module and setup a list of languages:

```php
'modules' => [
    // ...
    'i18n' => [
        'class' => 'metalguardian\i18n\Module',
        'languages' => ['en', 'uk', 'fr', 'es'],
    ],
],
```

If you set 'i18n' name to the module, you can simply call \metalguardian\i18n\Module::getUrl() to get
link to the translation controller
