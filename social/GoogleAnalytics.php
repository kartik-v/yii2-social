<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-social
 * @version 1.0.0
 */

namespace kartik\social;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Widget to embed Google Analytics tracking plugin on your website
 *
 * Usage:
 * ```
 * echo GoogleAnalytics::widget([
 *     'id' => 'TRACKING_ID',
 *     'domain' => 'TRACKING_DOMAIN'
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class GoogleAnalytics extends Widget
{

	/**
	 * @var string the Google Analytics Tracking ID
	 */
	public $id;

	/**
	 * @var string the domain name of your website where the tracking code will be displayed
	 */
	public $domain;

	/**
	 * @var boolean whether to insert the new version of the google analytics tracking code
	 */
	public $newVersion = true;

	/**
	 * @var boolean whether to insert the older version of the google analytics tracking code
	 */
	public $oldVersion = false;

	/**
	 * Initialize the widget
	 *
	 * @throws InvalidConfigException
	 */
	public function init()
	{
		$this->validPlugins = false;
		parent::init();
		$this->setConfig('googleAnalytics');
		if (empty($this->id)) {
			throw new InvalidConfigException("Google analytics tracking 'id' has not been set.");
		}
		if (empty($this->domain)) {
			throw new InvalidConfigException("Google analytics tracking 'domain' has not been set.");
		}
		$params = [
			'id' => $this->id,
			'domain' => $this->domain,
			'newVersion' => $this->newVersion,
			'oldVersion' => $this->oldVersion,
			'noscript' => $this->renderNoScript()
		];
		echo $this->render('google-analytics', $params);
	}

}