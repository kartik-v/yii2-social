yii2-social
===========
Module that enables access to social plugins for Yii Framework 2.0. It includes support for embedding plugins from the following networks into your website:

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
  - Signin Plugin
  - +1 Button
  - Share Button
  - Follow Button
  - Page Badge
  - Person/Profile Badge
  - Community Badge
  - Embedded Posts
- Google Analytics
- Twitter
  - Share Button
  - Follow Button
  - Hash Tag Button
  - Mention Button
  - Embedded Posts/Tweets
- GitHub
  - Watch Button
  - Fork Button
  - Follow Button

> NOTE: This extension depends on the [yiisoft/yii2](https://github.com/yiisoft/yii2/) package. Check the 
[composer.json](https://github.com/kartik-v/yii2-widgets/blob/master/composer.json) for this extension's requirements and dependencies. 
Note: Yii 2 framework is still in active development, and until a fully stable Yii2 release, your core yii2 packages (and its dependencies) 
may be updated when you install or update this extension. You may need to lock your composer package versions for your specific app, and test 
for extension break if you do not wish to auto update dependencies.
  
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
            'clientId' => 'GOOGLE_API_CLIENT_ID',
            'pageId' => 'GOOGLE_PLUS_PAGE_ID',
            'profileId' => 'GOOGLE_PLUS_PROFILE_ID',
        ],

        // the global settings for the google analytic plugin widget
        'googleAnalytics' => [
            'id' => 'TRACKING_ID',
            'domain' => 'TRACKING_DOMAIN',
        ],
        
        // the global settings for the twitter plugins widget
        'twitter' => [
            'screenName' => 'TWITTER_SCREEN_NAME'
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

### Twitter
[```VIEW DEMO```](http://demos.krajee.com/social-details/twitter)

### GitHub
[```VIEW DEMO```](http://demos.krajee.com/social-details/github)

## License

**yii2-social** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
