<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-markdown
 * @version 1.0.0
 */

namespace kartik\social;

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
	 * - fileUpload: boolean whether or not file uploads are enabled on your server.
	 * - allowSignedRequest: boolean whether or not to use signed_request data from query
	 *   parameters or the POST body. For security purposes, this should be set
	 *   to false for non-canvas apps. This is optional.
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
	 * @var object the Facebook API object
	 */
	private $_facebook;

	/**
	 * Returns the Facebook SDK API object
	 *
	 * @throws InvalidConfigException
	 */
	public function getFbApi()
	{
		$appId = null;
		$secret = null;
		$fileUpload = false;
		$allowSignedRequest = false;

		extract($this->facebook);

		if ($appId == null) {
			throw new InvalidConfigException("The Facebook 'appId' has not been set.");
		}
		if ($secret == null) {
			throw new InvalidConfigException("The Facebook 'secret' has not been set.");
		}
		if (!isset($this->_facebook)) {
			$path = Yii::getPathAlias('@vendor/facebook/php-sdk/src/facebook.php');
			require_once($path);
			$config = compact('appId', 'secret', 'fileUpload', 'allowSignedRequest');
			$this->_facebook = new Facebook($config);
		}
		return $this->_facebook;
	}

	/**
	 * Returns the Facebook User ID
	 *
	 * @return string
	 */
	public function getFbUser()
	{
		$facebook = $this->getFbApi();
		return $facebook->getUser();
	}

}