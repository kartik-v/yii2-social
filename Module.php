<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2016
 * @package yii2-social
 * @version 1.3.2
 */

namespace kartik\social;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use \Facebook\Facebook;
use yii\web\Session;

/**
 * Module for configuring all social widgets
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var array the disqus configuration. You can setup these keys. These can be overridden at the widget level.
     * - settings: array the configuration for the discus widget
     *   - shortname: string the disqus forum shortname
     *   - identifier: string the disqus identifier for your page
     *   - title: string the disqus title of the current page
     *   - url: string the URL of the current page. If not set will be set to
     *     the `window.location.href`
     *   - category_id: string the category to be used for the current page. This
     *     is used when creating the thread on Disqus for the first time.
     *   - disable_mobile: boolean disable use of mobile optimized version of Disqus.
     * - showCount: boolean whether to display the comment count summary instead of the detailed Disqus standard
     *     comments widget
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to false will not
     *     displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to `['class' => 'alert
     *     alert-danger']`.
     */
    public $disqus = [];

    /**
     * @var array the facebook api configuration. You can setup these keys:
     * - appId: string the Facebook Application ID. This is mandatory.
     * - secret: string the Facebook Application Secret. This is mandatory.
     * - default_graph_version: string, the default graph version. Defaults to `v2.5`.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $facebook = [];

    /**
     * @var array the google api configuration. You can setup these keys:
     * - clientId: string the Google Client ID. This is mandatory.
     * - secret: string the Google Client Application Secret. This is mandatory.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $google = [];

    /**
     * @var array the google analytics api configuration. You can setup these keys:
     * - id: string the Google Analytics Tracking ID.
     * - domain: string the domain name of your website where the tracking code will be displayed.
     * - newVersion: boolean whether to insert the new version of the google analytics tracking code. Defaults to
     *     `true`.
     * - oldVersion: boolean whether to insert the old version of the google analytics tracking code. Defaults to
     *     `false`.
     */
    public $googleAnalytics = [];

    /**
     * @var array the twitter api configuration. You can setup these keys:
     * - screenName: string the Twitter Screen Name. This is mandatory for
     *   follow, mention, and hashtag buttons.
     * - hashTag: string the Twitter Hash Tag.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $twitter = [];

    /**
     * @var array the VKontakte api configuration. You can setup these keys:
     * - apiId: string|int, the VK API identifier.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $vk = [];

    /**
     * @var array the github buttons api configuration. You can setup these keys:
     * - type: string the Github button type.
     * - settings: array the configuration for the GitHub buttons widget
     * - options: array the HTML attributes for the GitHub buttons iframe container.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $github = [];

    /**
     * @var array the github extended alternative buttons api configuration. You can setup these keys:
     * - type: string the Github button type.
     * - user: string the Github user name.
     * - repo: string the Github repo name.
     * - settings: array the configuration for the GitHub buttons widget
     * - options: array the HTML attributes for the GitHub buttons iframe container.
     * - noscript: string / bool, text to be displayed if browser does not support javascript. If set to `false`, this
     *      will not displayed.
     * - noscriptOptions: array, HTML attributes for the noscript message container. Defaults to:
     *     `['class' => 'alert alert-danger']`.
     */
    public $githubX = [];

    /**
     * @var Facebook the Facebook object
     */
    private $_fbObject;

    /**
     * Gets the Facebook object based on supplied parameters or uses module level facebook settings
     *
     * @param array $params the parameters to be set for the facebook session. If not set, will use the module level
     *     facebook settings.
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
     *     the `$params` should be set as `$key => $value` pairs, where `$key` is one of:
     * - 'app_id': string, the facebook application id (if not set, this will default from module facebook settings)
     * - 'app_secret': string, the facebook application secret (if not set, this will default from module facebook
     *     settings)
     * - 'default_graph_version': string, the default facebook graph version. Defaults to 'v2.5'. This typically must
     *     be set to the latest facebook graph version.
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
        $default_graph_version = 'v2.5';
        extract($params);
        static::checkFbConfig('appId', $appId);
        static::checkFbConfig('secret', $secret);
        static::checkFbConfig('default_graph_version', $default_graph_version);
        $params['app_id'] = $params['appId'];
        $params['app_secret'] = $params['secret'];
        if (!isset($params['persistent_data_handler'])) {
            $params['persistent_data_handler'] = 'session';
        }
        unset($params['appId'], $params['secret']);
        $persistence = ArrayHelper::getValue($params, 'persistent_data_handler');
        if ($persistence === 'session' && !session_id()) {
            $session = new Session;
            $session->open();
        }
        $this->_fbObject = new Facebook($params);
    }

    /**
     * Generates and returns a facebook login link
     *
     * @param string   $callback the absolute callback url action that will be used by Facebook SDK redirect login
     *     helper.
     * @param array    $options the HTML attributes for the login link. The following special options are recognized:
     *     - `label`: string, the label to display for the link. Defaults to 'Login with Facebook'.
     * @param array    $permissions the permissions for the user to be authenticated by the login helper. Defaults to
     *     `['email', 'user_posts']`.
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
}
