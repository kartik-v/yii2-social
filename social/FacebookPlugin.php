<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-social
 * @version 1.0.0
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Widget to render various Facebook plugins
 * 
 * Usage:
 * ```
 * echo FacebookPlugin::widget([
 *     'appId' => 'FACEBOOK_APP_ID',
 *     'type' => FacebookPlugin::COMMENT,
 *     'settings' => ['colorscheme' => 'dark']
 * ]);
 * ```
 * 
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FacebookPlugin extends SocialWidget {

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
     * @var string the name of the Facebook API Module
     */
    public $moduleName = 'facebook';

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        $module = Yii::$app->getModule($this->moduleName);
        if ($module !== null && empty($this->appId)) {
            $this->appId = $module->appId;
        }
        if (empty($this->appId)) {
            throw new InvalidConfigException("The Facebook 'appId' has not been set.");
        }
        $settings = ['class' => $this->type];
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('social', 
                'Please enable JavaScript on your browser to view the Facebook {pluginName} plugin correctly on this site.', 
                ['pluginName' => Yii::t('social', str_replace('fb-', '', $this->type))]
            );
        }
        foreach ($this->settings as $key => $value) {
            $settings["data_{$key}"] = $value;
        }
        $this->registerAssets();
        echo "<div id='fb-root'></div>\n" .
        Html::tag('div', '', $settings) . "\n" .
        "<noscript>" . Html::tag('div', $this->noscript, $this->noscriptOptions) . "</noscript>";
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
