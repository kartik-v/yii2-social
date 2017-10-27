Change Log: `yii2-social`
=========================

## Version 1.3.4

**Date:** 27-Oct-2017

- (enh #74): Update disqus plugin initialization.
- (enh #69): Update Czech Translations.

## Version 1.3.3

**Date:** 15-Mar-2017

- (enh #68): New `FacebookPlugin::SAVE` button.
- Update message config to include all default standard translation files.
- (enh #67): Add new class `kartik\social\FacebookPersistentHandler`.
- (enh #66): Add Lithuanian Translations.
- (enh #64): Add Dutch Translations.
- (enh #63): Add Polish Translations.
- (enh #62): Update Facebook SDK to release v5.0.

## Version 1.3.2

**Date:** 16-Aug-2016

- (enh #42): Add Italian Translations.
- (enh #43): Fix VK styles.
- (enh #46): Update to Facebook SDK release v5.0. (BC Breaking Change).
- (enh #47): Update Facebook Plugin for latest FB graph ## Version (BC Breaking Change).
- Add branch alias for dev-master latest release.
- (bug #51): Fix VkPlugin undefined variable error.
- (enh #56): Auto set right Facebook language.
- (enh #57): Initialize session via Yii standard methods.
- (enh #58): Add Brazilian Portuguese Translations.
- (enh #59): Add Portuguese Translations.
- (bug #60): Correct noscript validation.
- Add contribution templates.

## Version 1.3.1

**Date:** 19-Jul-2015

- (enh #21): Implement VKontakte plugin.
- (enh #26): Add option to disable credits after Disqus widget.
- Update copyright year till current.
- (enh #29): Validate Disqus comment rendering for older IE browsers.
- (enh #30): Generate default `en` message translation file.
- (enh #32): Enhancements to generate Facebook session better.
- (enh #33): Add Spanish translations.
- (enh #35): Better composer ## Version dependencies for Facebook SDK.
- (enh #36): Add `FacebookPlugin::async` property.

## Version 1.3.0

**Date:** 13-Feb-2015

- (enh #6): Remove Facebook PHP SDK support (old ## Version v3.0). 
- (enh #10): German translations added
- (enh #11): Hungarian translations added
- (enh #12): Ukranian & Russian translations added
- (enh #13): New features for PHP SDK v4.0
    - Implement an extended `FacebookRedirectLoginHelperX` class for handling sessions the Yii way
    - New `getFbLoginHelper` method in social module to fetch the Redirect Login Helper object instance based on the module level facebook settings
    - New `initFbSession` method in social module to initialize a facebook session
    - New `getFbSession` method in social module to get a facebook session either based on string token, redirect login url, canvas login, or Javascript login.
    - New `getFbGraphUser` method gets the Facebook graph user object for current user.
    - Changes from earlier release
        - Method `getFbApi`has been removed from the social module
        - Method `getFbUser` has been removed from the social module
        - Set release to stable.
- (enh #14): Add new configuration for GoogleAnalytics `anonymizeIp`.
- (enh #15): Various enhancements and additional configuration settings for the `GoogleAnalytics` widget:
    - Remove support for old ## Version (widget only supports the new Google Universal Analytics plugin)
    - New `testMode` property that defaults to `YII_DEBUG` definition to allow google analytics to work from localhost.
    - Ability to configure tracking object name. Defaults to `__gaTracker`.
    - Ability to configure `trackerConfig` settings for creation of the ga object.
    - Ability to configure `sendConfig` settings for sending of the ga data.
    - Ability to configure `anonymizeIp` property.
    - Ability to add additional javascript **before** sending the ga data. 
    - Ability to add additional javascript **after** sending the ga data.
- (enh #18): Separate Github star and watch buttons as per v2.0.
- (enh #19): Implement GithubXPlugin based on [github buttons](https://github.com/ntkme/github-buttons).
- (enh #20): Allow `social` module to be used as an embedded submodule.
- (enh #22): Set dependency on kartik-v/yii2-krajee-base.
- (enh #23): Enhance Facebook plugin and fix comments plugin width property.
- Set copyright year to current.

## Version 1.2.0

**Date:** 10-Nov-2014

- Set release to stable

## Version 1.1.0

**Date:** 01-Jul-2014

- PSR4 alias change
- (enh #8): Allow language configuration for Facebook, Twitter, and Google plugins.

## Version 1.0.0

**Date:** 01-Dec-2013

- Initial release
