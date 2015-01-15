<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-social
 * @version 1.3.0
 */

namespace kartik\social;

use Yii;
use yii\helpers\ArrayHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

/**
 * Module for configuring all social widgets
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{

    /**
     * @var array the disqus configuration. You can setup these keys. These can
     * be overridden at the widget level.
     * - settings: array the configuration for the discus widget
     *   - shortname: string the disqus forum shortname
     *   - identifier: string the disqus identifier for your page
     *   - title: string the disqus title of the current page
     *   - url: string the URL of the current page. If not set will be set to
     *     the `window.location.href`
     *   - category_id: string the category to be used for the current page. This
     *     is used when creating the thread on Disqus for the first time.
     *   - disable_mobile: boolean disable use of mobile optimized version of Disqus.
     * - showCount: boolean whether to display the comment count summary instead
     *   of the detailed Disqus standard comments widget
     * - noscript: string/boolean text to be displayed if browser does not support
     *   javascript. If set to false will not displayed.
     * - noscriptOptions: array HTML attributes for the noscript message container.
     *   Defaults to ['class' => 'alert alert-danger'].
     */
    public $disqus = [];

    /**
     * @var array the facebook api configuration. You can setup these keys:
     * - appId: string the Facebook Application ID. This is mandatory.
     * - secret: string the Facebook Application Secret. This is mandatory.
     * - noscript: string/boolean text to be displayed if browser does not support
     *   javascript. If set to false will not displayed.
     * - noscriptOptions: array HTML attributes for the noscript message container.
     *   Defaults to ['class' => 'alert alert-danger'].
     */
    public $facebook = [];

    /**
     * @var array the google api configuration. You can setup these keys:
     * - clientId: string the Google Client ID. This is mandatory.
     * - secret: string the Google Client Application Secret. This is mandatory.
     * - noscript: string/boolean text to be displayed if browser does not support
     *   javascript. If set to false will not displayed.
     * - noscriptOptions: array HTML attributes for the noscript message container.
     *   Defaults to ['class' => 'alert alert-danger'].
     */
    public $google = [];

    /**
     * @var array the google analytics api configuration. You can setup these keys:
     * - id: string the Google Analytics Tracking ID.
     * - domain: string the domain name of your website where the tracking code will be displayed.
     * - newVersion: boolean whether to insert the new version of the google analytics tracking code.
     *   Defaults to true.
     * - oldVersion: boolean whether to insert the old version of the google analytics tracking code.
     *   Defaults to false.
     */
    public $googleAnalytics = [];

    /**
     * @var array the twitter api configuration. You can setup these keys:
     * - screenName: string the Twitter Screen Name. This is mandatory for
     *   follow, mention, and hashtag buttons.
     * - $hashTag: string the Twitter Hash Tag.
     * - noscript: string/boolean text to be displayed if browser does not support
     *   javascript. If set to false will not displayed.
     * - noscriptOptions: array HTML attributes for the noscript message container.
     *   Defaults to ['class' => 'alert alert-danger'].
     */
    public $twitter = [];

    /**
     * @var array the github buttons api configuration. You can setup these keys:
     * - type: string the Twitter Screen Name. This is mandatory for
     *   follow, mention, and hashtag buttons.
     * - settings: array the configuration for the GitHub buttons widget
     * - options: array the HTML attributes for the GitHub buttons iframe container.
     * - noscript: string/boolean text to be displayed if browser does not support
     *   javascript. If set to false will not displayed.
     * - noscriptOptions: array HTML attributes for the noscript message container.
     *   Defaults to ['class' => 'alert alert-danger'].
     */
    public $github = [];

    /**
     * @var FacebookSession the Facebook session object
     */
    private $_fbSession;

    /**
     * @var GraphUser the Facebook graph object for current user
     */
    private $_fbGraphUser; 
    
    /**
     * Returns the Facebook Session object
     *
     * @param array $params should be set as $key=>$value,
     * where $key is one of:
     * - 'appId': string, the facebook application id (if not set, this  
     *    will default from module facebook settings) 
     * - 'secret': string, the facebook application secret (if not set, this 
     *    will default from module facebook settings)
     *
     * @return void
     *
     * @throws InvalidConfigException
     */
    public function initFbSession($params = [])
    {
        $params += $this->facebook;
        $appId = null;
        $secret = null;
        extract($params);
        if (empty($appId)) {
            throw new InvalidConfigException("The Facebook 'appId' has not been set.");
        }
        if (empty($secret)) {
            throw new InvalidConfigException("The Facebook 'secret' has not been set.");
        }
        FacebookSession::setDefaultApplication($appId, $secret);
    }
    
    /**
     * Returns the Facebook Session object.
     *
     * @param string source string|FacebookRedirectLoginHelper|FacebookCanvasLoginHelper|FacebookJavaScriptLoginHelper 
     *    the token or the helper instance. If its provided as a string, then it will be assumed to be a
     *    valid access token based on which session will be returned. Else, it will be derived from one of the helper
     *    objects provided.
     *
     * @return FacebookSession
     *
     * @throws InvalidConfigException
     */
    public function getFbSession($source)
    {
        if (isset($this->_fbSession)) {
            return $this->_fbSession;
        }
        if (
            empty($source) || !is_string($source) || 
            !($source instanceof FacebookRedirectLoginHelper) || 
            !($source instanceof FacebookCanvasLoginHelper) || 
            !($source instanceof FacebookJavaScriptLoginHelper)
        ) {
            return null;
        }
        if (is_string($source)) {
            $this->_fbSession = new FacebookSession($source);            
        } else {
            $this->_fbSession = $source->getSession();
        }
        return $this->_fbSession;
    }
    
    /**
     * Gets the Yii modified redirect login helper
     *
     * @param string $url the absolute url to redirect to
     * @param array $params  should be set as $key=>$value,
     * where $key is one of:
     * - 'appId': string, the facebook application id (if not set, this
     *    will default from module facebook settings) 
     * - 'secret': string, the facebook application secret (if not set, this 
     *    will default from module facebook settings)
     * @return FacebookRedirectLoginHelperX
     */
    public function getFbLoginHelper($url = '', $params = []) {
        $params += $this->facebook;
        extract($params);
        return new FacebookRedirectLoginHelperX($url, $appId, $secret);
    }
    
    /**
     * Returns the Facebook graph user object for current user
     *
     * @param FacebookSession $session the Facebook session instance
     *
     * @return GraphUser
     */
    public function getFbGraphUser($session)
    {
        return (new FacebookRequest(
            $session, 'GET', '/me'
        ))->execute()->getGraphObject(GraphUser::className());
    }

}