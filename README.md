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
         // a list of languages
        'languages' => ['en', 'uk', 'fr', 'es'],
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
        'languages' => ['en', 'uk', 'fr', 'es'],
    ],
],
```

This will override all other message sources and setup '*' source, which handle all translations in application

If you don't have message tables in your database yet, you may use migrations:

```php
./yii migrate --migrationPath=@vendor/metalguardian/yii2-i18n-module/src/migrations
```

In admin application you need to configure translation module:

```php
'modules' => [
    // ...
    'i18n' => [
        'class' => 'metalguardian\i18n\Module',
    ],
],
```

If you set 'i18n' name to the module, you can simply call \metalguardian\i18n\Module::getUrl() to get
link to the translation controller

Using this module you can export and import translations from and into database:

Export all messages from database:
```php
./yii message/export ./common/messages
```

Export only `app` category from database:
```php
./yii message/export ./common/messages app
```

Import translations from all files in ./vendor/yiisoft/yii2/messages/. Script will ask you some questions,
like category message and language for the importing file
```php
./yii message/import ./vendor/yiisoft/yii2/messages/
```

You can add `override` argument to override existing translations with translations stored in files
```php
./yii message/import ./vendor/yiisoft/yii2/messages/   1
```

Or you can import only one file. Than you need to specify all parameters as arguments:
```php
./yii message/import-file ./vendor/yiisoft/yii2/messages/uk/yii.php yii uk
```

And if you need override existing translations:
```php
./yii message/import-file ./vendor/yiisoft/yii2/messages/uk/yii.php yii uk 1
```
