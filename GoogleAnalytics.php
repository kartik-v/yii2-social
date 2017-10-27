<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;

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
    const HIT_NONE = 0;
    const HIT_ALL = 1;
    const HIT_EACH = 2;

    /**
     * @var string the Google Analytics Tracking ID
     */
    public $id;

    /**
     * @var string the domain name of your website where the tracking code will be displayed
     */
    public $domain;
    
    /**
     * @var string the global object name. Defaults to `__gaTracker`.
     */
    public $objectName = '__gaTracker';
    
    /**
     * @var bool whether to enable test mode to automatically set `cookieDomain`. 
     * The test mode will auto default to `YII_DEBUG` definition.
     */
    public $testMode = YII_DEBUG;
    
    /**
     * @var int the settings to anonymize the IP address of the hit (http request) 
     * sent to Google Analytics. One of the following values are supported:
     * - `0` or `GoogleAnalytics::HIT_NONE`: The IP will not be anonymized.
     * - `1` or `GoogleAnalytics::HIT_ALL`: Anonymize the IP addresses for all the 
     *    hits sent from a page (the lifetime of the tracker object)
     * - `2` or `GoogleAnalytics::HIT_EACH`: Anonymize the IP address of an individual hit
     */
    public $anonymizeIp = self::HIT_NONE;
    
    /**
     * @var array|string the tracker object configuration. Set it as a string (to be used as is)
     * or an associative array  of `$key => $value`.
     *
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#create
     */
    public $trackerConfig = [];
    
    /**
     * @var array|string  the configuration for sending data. Set it as a string (to be used as is)
     * or an associative array  of `$key => $value`.
     *
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#send
     */
    public $sendConfig = [];

    /**
     * @var string any javascript to prepend before the script that sends data
     */
    public $jsBeforeSend = '';

    /**
     * @var string any javascript to append after the script that sends data
     */
    public $jsAfterSend = '';

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
        if ($this->anonymizeIp === self::HIT_ALL) {
            $this->jsBeforeSend .= "{$this->objectName}('set', 'anonymizeIp', true);\n";
        } elseif ($this->anonymizeIp === self::HIT_EACH && !is_string($this->sendConfig)) {
            $this->sendConfig['anonymizeIp'] = true;
        }
        if ($this->testMode === true && !is_string($this->trackerConfig)) {
            $this->trackerConfig['cookieDomain'] = 'none';
        }
        $trackerConfig = empty($this->trackerConfig) ? '' : ', ' .
            (is_array($this->trackerConfig) ? Json::encode($this->trackerConfig) : $this->trackerConfig);
        $sendConfig = empty($this->sendConfig) ? '' : ', ' .
            (is_array($this->sendConfig) ? Json::encode($this->sendConfig) : $this->sendConfig);
        $params = [
            'id' => $this->id,
            'domain' => $this->domain,
            'anonIp' => $this->anonymizeIp,
            'obj' => $this->objectName,
            'trackerConfig' => $trackerConfig,
            'sendConfig' => $sendConfig,
            'jsBeforeSend' => $this->jsBeforeSend,
            'jsAfterSend' => $this->jsAfterSend,
            'noscript' => $this->renderNoScript()
        ];
        echo $this->render('google-analytics', $params);
    }
}