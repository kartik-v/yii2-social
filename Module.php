<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use Facebook\Facebook;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Module for configuring all social widgets
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    /**
     * Default facebook graph api version
     */
    const FB_GRAPH_VER = 'v2.8';

    /**
     * @var array the disqus configuration. You can setup these keys. These can be overridden at the widget level.
     * - `settings`: _array_, the configuration for the discus widget
     *   - `shortname`: _string_, the disqus forum shortname
     *   - `identifier`: _string_, the disqus identifier for your page
     *   - `title`: _string_, the disqus title of the current page
     *   - `url`: _string_, the URL of the current page. If not set will be set to value of `window.location.href`.
     *   - `category_id: _string_, the category to be used for the current page. This
     *     is used when creating the thread on Disqus for the first time.
     *   - `disable_mobile: _boolean_, disable use of mobile optimized version of Disqus.
     * - `showCount`: _boolean_, whether to display the comment count summary instead of the detailed Disqus standard
     *   comments widget.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $disqus = [];

    /**
     * @var array the facebook api configuration. You can setup these keys:
     * - `app_id` or `appId`: _string_, the Facebook Application ID. This is mandatory.
     * - `app_secret` or `secret`: _string_, the Facebook Application Secret. This is mandatory.
     * - `default_graph_version: _string_, the default graph version. Defaults to [[FB_GRAPH_VER]].
     * - `default_access_token`: _string_, the default facebook access token (optional).
     * - `persistent_data_handler`: _Facebook\PersistentData\PersistentDataInterface_, defaults to new class instance of
     *    `kartik\social\FacebookPersistentHandler`.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $facebook = [];

    /**
     * @var array the google api configuration. You can setup these keys:
     * - clientId`: _string_, the Google Client ID. This is mandatory.
     * - secret`: _string_, the Google Client Application Secret. This is mandatory.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`, this
     *   will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $google = [];

    /**
     * @var array the google analytics api configuration. You can setup these keys:
     * - `id`: _string_, the Google Analytics Tracking ID.
     * - `domain`: _string_, the domain name of your website where the tracking code will be displayed.
     * - `newVersion`: _boolean_, whether to insert the new version of the google analytics tracking code. Defaults to
     *   `true`.
     * - `oldVersion`: _boolean_, whether to insert the old version of the google analytics tracking code. Defaults to
     *   `false`.
     */
    public $googleAnalytics = [];

    /**
     * @var array the twitter api configuration. You can setup these keys:
     * - `screenName`: _string_, the Twitter Screen Name. This is mandatory for follow, mention, and hashtag buttons.
     * - `hashTag`: _string_, the Twitter Hash Tag.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $twitter = [];

    /**
     * @var array the VKontakte api configuration. You can setup these keys:
     * - `apiId: string|int, the VK API identifier.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $vk = [];

    /**
     * @var array the github buttons api configuration. You can setup these keys:
     * - type`: _string_, the Github button type.
     * - settings`: _array_, the configuration for the GitHub buttons widget
     * - options`: _array_, the HTML attributes for the GitHub buttons iframe container.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $github = [];

    /**
     * @var array the github extended alternative buttons api configuration. You can setup these keys:
     * - `type`: _string_, the Github button type.
     * - `user`: _string_, the Github user name.
     * - `repo`: _string_, the Github repo name.
     * - `settings`: _array_, the configuration for the GitHub buttons widget
     * - `options`: _array_, the HTML attributes for the GitHub buttons iframe container.
     * - `noscript`: _string|boolean_, text to be displayed if browser does not support javascript. If set to `false`,
     *   this will not displayed.
     * - `noscriptOptions`: _array_, HTML attributes for the noscript message container. Defaults to:
     *   `['class' => 'alert alert-danger']`.
     */
    public $githubX = [];

    /**
     * @var Facebook the Facebook object
     */
    private $_fbObject;

    /**
     * Check if a facebook configuration variable is set
     *
     * @param string $var the variable name in the configuration
     * @param string $val the variable value to test
     *
     * @throws InvalidConfigException
     */
    protected static function checkFbConfig($var = '', $val = null)
    {
        if (!isset($val)) {
            throw new InvalidConfigException("The Facebook '{$var}' has not been set.");
        }
    }

    /**
     * Gets the Facebook object based on supplied parameters or uses module level facebook settings
     *
     * @param array $params the parameters to be set for the facebook session. If not set, will use the module level
     * facebook settings.
     *
     * @return Facebook object
     *
     * @throws InvalidConfigException
     */
    public function getFb($params = [])
    {
        if (!isset($this->_fbObject)) {
            $this->setFb($params);
        }
        return $this->_fbObject;
    }

    /**
     * Sets the Facebook object based on supplied parameters or uses module level facebook settings
     *
     * @param array|null $params , if set to null the facebook object will be set to a null value. If set as an array,
     *   the `$params` should be set as `$key => $value` pairs, where `$key` is one of:
     * - `app_id`: _string_, the facebook application id (if not set, this will default from module facebook settings)
     * - `app_secret`: _string_, the facebook application secret (if not set, this will default from module facebook
     *   settings)
     * - `default_graph_version`: _string_, the default facebook graph version. Defaults to [[FB_GRAPH_VER]]. This
     *   typically must be set to the latest facebook graph version.
     * - `default_access_token`: _string_, the default facebook access token (optional).
     * - `persistent_data_handler`: _Facebook\PersistentData\PersistentDataInterface_, defaults to new class instance of
     *    `kartik\social\FacebookPersistentHandler`.
     */
    public function setFb($params = [])
    {
        if ($params === null) {
            $this->_fbObject = null;
            return;
        }
        $params += $this->facebook;
        $appId = null;
        $secret = null;
        $default_graph_version = self::FB_GRAPH_VER;
        extract($params);
        static::checkFbConfig('appId', $appId);
        static::checkFbConfig('secret', $secret);
        static::checkFbConfig('default_graph_version', $default_graph_version);
        if (!isset($params['app_id'])) {
            $params['app_id'] = $params['appId'];
        }
        if (!isset($params['app_secret'])) {
            $params['app_secret'] = $params['secret'];
        }
        if (!isset($params['persistent_data_handler'])) {
            $params['persistent_data_handler'] = new FacebookPersistentHandler();
        }
        unset($params['appId'], $params['secret']);
        $this->_fbObject = new Facebook($params);
    }

    /**
     * Generates and returns a facebook login link
     *
     * @param string $callback the absolute callback url action that will be used by Facebook SDK redirect login helper.
     * @param array $options the HTML attributes for the login link. The following special options are recognized:
     *   - `label`: _string_, the label to display for the link. Defaults to 'Login with Facebook'.
     * @param array $permissions the permissions for the user to be authenticated by the login helper. Defaults to
     *   `['email', 'user_posts']`.
     * @param Facebook $fb the facebook object. If not provided will default to the object retrieved by `getFb` method.
     *
     * @return string the generated login link
     */
    public function getFbLoginLink($callback = '#', $options = [], $permissions = ['email', 'user_posts'], $fb = null)
    {
        if ($fb === null) {
            $fb = $this->getFb();
        }
        $url = $fb->getRedirectLoginHelper()->getLoginUrl($callback, $permissions);
        $label = ArrayHelper::remove($options, 'label', Yii::t('kvsocial', 'Login with Facebook'));
        return Html::a($label, $url, $options);
    }
}
