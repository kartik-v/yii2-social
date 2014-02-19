yii2-social
===========
Module that enables access to social plugins for Yii Framework 2.0. . It includes support for embedding plugins from the following networks into your website:

- Disqus
- Facebook  
  - Like Button
  - Share Button
  - Send Button
  - Embedded Posts
  - Follow Button
  - Comment Button
  - Activity Feed
  - Recommendations Feed
  - Recommendations Bar
  - Like Box
  - Face Pile
- Google Plus
- Google Analytics


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require kartik-v/yii2-social "dev-master"
```

or add

```
"kartik-v/yii2-social": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### Module Configuration
You can view [usage and demos](http://demos.krajee.com/social) on the module.
```php
'modules' => [
    'social' => [
        // the module class
        'class' => 'kartik\social\Module',

        // the global settings for the disqus widget
        'disqus' => [
            'settings' => ['shortname' => 'DISQUS_SHORTNAME'] // default settings
        ],

        // the global settings for the facebook plugins widget
        'facebook' => [
            'appId' => 'FACEBOOK_APP_ID',
            'secret' => 'FACEBOOK_APP_SECRET',
        ],

        // the global settings for the google plugins widget
        'google' => [
            'pageId' => 'GOOGLE_PLUS_PAGE_ID',
            'clientId' => 'GOOGLE_API_CLIENT_ID',
        ],

        // the global settings for the google analytic plugin widget
        'googleAnalytics' => [
            'id' => 'TRACKING_ID',
            'domain' => 'TRACKING_DOMAIN',
        ],
    ],
    // your other modules
]
```

### Disqus
[```VIEW DEMO```](http://demos.krajee.com/social-details/disqus)

### Facebook
[```VIEW DEMO```](http://demos.krajee.com/social-details/facebook)

### Google+
[```VIEW DEMO```](http://demos.krajee.com/social-details/google)

### Google Analytics
[```VIEW DEMO```](http://demos.krajee.com/social-details/google-analytics)