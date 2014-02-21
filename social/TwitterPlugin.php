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
 * Widget to render various Twitter plugins
 * 
 * Usage:
 * ```
 * echo TwitterPlugin::widget([
 *     'screenName' => 'TWITTER_SCREEN_NAME',
 *     'type' => TwitterPlugin::MENTION,
 *     'settings' => ['size' => 'large']
 * ]);
 * ```
 * 
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class TwitterPlugin extends Widget
{

    const SHARE = 'twitter-share-button';
    const FOLLOW = 'twitter-follow-button';
    const HASHTAG = 'twitter-hashtag-button';
    const MENTION = 'twitter-mention-button';
    const TWEET = 'twitter-tweet';

    /**
     * @var string the Twitter plugin type
     * defaults to Twitter Share (Tweet) button
     */
    public $type = self::SHARE;

    /**
     * @var string the Twitter hash tag 
     * (to be used for hashtag button)
     */
    public $hashTag;

    /**
     * @var string the Twitter screen name
     * (to be used for follow, hashtag & mention buttons)
     */
    public $screenName;

    /**
     * @var array the Twitter plugin settings
     */
    public $settings = [];

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->validPlugins = [
            self::SHARE,
            self::FOLLOW,
            self::HASHTAG,
            self::MENTION,
            self::TWEET,
        ];
        parent::init();
        $this->tag = ($this->type === self::TWEET) ? 'blockquote' : 'a';
        $this->setConfig('twitter');
        if ($this->type === self::HASHTAG && empty($this->hashTag) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Twitter 'hashTag' must be set for displaying the 'hashtag' button.");
        }
        if (($this->type === self::FOLLOW || $this->type === self::HASHTAG || $this->type === self::MENTION) && empty($this->screenName) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Twitter 'screenName' must be set for displaying the " . str_replace('-', ' ', $this->type) . ".");
        }
        if ($this->type === self::TWEET && empty($this->content)) {
            throw new InvalidConfigException("The Twitter 'content' must be set for displaying 'embedded tweets'.");
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('social', 'Please enable JavaScript on your browser to view the Twitter {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('social', str_replace('twitter-', '', $this->type))]
            );
        }
        $this->registerAssets();
        $this->setPluginOptions();
        if ($this->type === self::FOLLOW) {
            $this->options['href'] = 'https://twitter.com/' . Html::encode($this->screenName);
        }
        elseif ($this->type === self::HASHTAG) {
            $this->options['href'] = 'https://twitter.com/intent/tweet?button_hashtag=' . Html::encode($this->hashTag);
            $this->options['data-related'] = empty($this->options['data-related']) ? $this->screenName : $this->screenName . ',' . $this->options['data-related'];
        }
        elseif ($this->type === self::MENTION) {
            $this->options['href'] = 'https://twitter.com/intent?screen_name=' . Html::encode($this->screenName);
        }
        echo $this->renderPlugin();
    }

    /**
     * Registers the necessary assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        $js = <<< SCRIPT
!function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    p = /^http:/.test(d.location) ? 'http' : 'https';
    if (!d.getElementById(id)) {
        js = d.createElement(s);
        js.id = id;
        js.src = p + '://platform.twitter.com/widgets.js';
        fjs.parentNode.insertBefore(js, fjs);

    }
}
(document, "script", "twitter-wjs");
SCRIPT;
        $view->registerJs($js);
    }

}
?>