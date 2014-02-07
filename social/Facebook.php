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
 * Widget to render various Facebook plugins
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Facebook extends \yii\base\Widget {

    const LIKE = 'fb-like';
    const SHARE = 'fb-share-button';
    const SEND = 'fb-send';
    const POST = 'fb-post';
    const FOLLOW = 'fb-follow';
    const COMMENT = 'fb-comments';
    const ACTIVITY = 'fb-activity';
    const RECO = 'fb-recommendations';
    const RECO_BAR = 'fb-recommendations-bar';
    const LIKE_BOX = 'fb-like-box';
    const FACEPILE = 'fb-facepile';

    /**
     * @var string the Facebook Application ID. 
     * This is mandatory.
     */
    public $appId;

    /**
     * @var string the Facebook plugin type
     * defaults to Facebook Comments
     */
    public $type = self::COMMENT;

    /**
     * @var array the Facebook plugin settings
     */
    public $settings = [];

    /**
     * @var string text to be displayed if browser does not support javascript 
     */
    public $noscript;

    /**
     * @var string HTML attributes for the noscript message container
     */
    public $noscriptOptions = ['class' => 'alert alert-danger'];

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        if (empty($this->appId)) {
            throw new InvalidConfigException("The Facebook 'appId' must be defined.");
        }
        $settings = ['class' => $this->type];
        if (!isset($this->noscript)) {
            $this->noscript = 'Please enable JavaScript on your browser to view the Facebook ' .
                    str_replace('fb-', '', $this->type) . ' plugin.';
        }
        foreach ($this->settings as $key => $value) {
            $settings["data_{$key}"] = $value;
        }
        $this->registerAssets();
        echo "<div id='fb-root'></div>\n" .
        Html::tag('div', '', $settings) . "\n<noscript>" .
        Html::tag('div', $this->noscript, $this->noscriptOptions) .
        '</noscript>';
    }

    /**
     * Registers the necessary assets
     */
    protected function registerAssets() {      
        $view = $this->getView();
        $js = <<< SCRIPT
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={$this->appId}";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));                
SCRIPT;
        $view->registerJs($js);
    }
}
