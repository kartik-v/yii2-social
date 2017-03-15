yii2-social
===========

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-social/v/stable)](https://packagist.org/packages/kartik-v/yii2-social)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-social/v/unstable)](https://packagist.org/packages/kartik-v/yii2-social)
[![License](https://poser.pugx.org/kartik-v/yii2-social/license)](https://packagist.org/packages/kartik-v/yii2-social)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-social/downloads)](https://packagist.org/packages/kartik-v/yii2-social)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-social/d/monthly)](https://packagist.org/packages/kartik-v/yii2-social)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-social/d/daily)](https://packagist.org/packages/kartik-v/yii2-social)

Module that enables access to social plugins for Yii Framework 2.0. It includes support for embedding plugins from the following networks into your website:

- Disqus
- Facebook  
  - Like Button
  - Share Button
  - Send Button
  - Save Button
  - Embedded Posts
  - Embedded Videos
  - Follow Button
  - Comment Button
  - Page Plugin
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
  - Embedded Timeline
- VKontakte
  - Comments Button
  - Embedded Post
  - Community/Group
  - Like Button
  - Recommendations
  - Poll Module
  - Authorization
  - Share Content
  - Subscribe  
- GitHub
  - Watch Button
  - Star Button
  - Fork Button
  - Follow Button
- GitHubX
  - Watch Button
  - Star Button
  - Fork Button
  - Issue Button
  - Download Button
  - Follow Button
  
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> NOTE: Check the [composer.json](https://github.com/kartik-v/yii2-social/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require kartik-v/yii2-social "@dev"
```

or add

```
"kartik-v/yii2-social": "@dev"
```

to the `require` section of your `composer.json` file.

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
            'app_id' => 'FACEBOOK_APP_ID',
            'app_secret' => 'FACEBOOK_APP_SECRET',
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

### GitHubX
[```VIEW DEMO```](http://demos.krajee.com/social-details/githubx)

## License

**yii2-social** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.