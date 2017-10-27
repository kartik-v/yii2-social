<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

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
    const TIMELINE = 'twitter-timeline';

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
     * @var string the additional configuration options for TwitterPlugin::TIMELINE, in 
     * case you do not wish to use a user timeline. One of the following settings may be 
     * set:
     * - `listSlug`: string, the list slug and applicable only if you want to use embedded list timeline.
     *   @see https://dev.twitter.com/web/embedded-timelines/list
     * - `search`: string, the search hash tag, if you want to use embedded search timeline.
     *   @see https://dev.twitter.com/web/embedded-timelines/search
     * - `collectionId`: string, the collection identifier, if you want to use embedded collection timeline.
     *   @see https://dev.twitter.com/web/embedded-timelines/collection
     * - `collectionName`: string, the collection name, if you want to use embedded collection timeline.
     *   @see https://dev.twitter.com/web/embedded-timelines/collection
     */
    public $timelineConfig = [];

    /**
     * Initialize the widget
     *
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
            self::TIMELINE
        ];
        parent::init();
        $this->tag = ($this->type === self::TWEET) ? 'blockquote' : 'a';
        $this->setConfig('twitter');
        $this->settings['lang'] = $this->language;
        if ($this->type === self::HASHTAG && empty($this->hashTag) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Twitter 'hashTag' must be set for displaying the 'hashtag' button.");
        }
        if ($this->type !== self::SHARE && $this->type !== self::TWEET && empty($this->screenName)) {
            throw new InvalidConfigException("The Twitter 'screenName' must be set for displaying the " . str_replace('-', ' ', $this->type) . ".");
        }
        if ($this->type === self::TWEET && empty($this->content)) {
            throw new InvalidConfigException("The Twitter 'content' must be set for displaying 'embedded tweets'.");
        }
        if ($this->type === self::TIMELINE) {
            if (empty($this->settings['widget-id'])) {
                throw new InvalidConfigException("The Twitter \"settings['widget-id']\" must be set for displaying the twitter timeline.");
            }
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('kvsocial', 'Please enable JavaScript on your browser to view the Twitter {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('kvsocial', str_replace('twitter-', '', $this->type))]
            );
        }
        $this->registerAssets();
        $this->setPluginOptions();
        $screenName = Html::encode($this->screenName);
        switch($this->type) {
            case self::FOLLOW:
                $this->options['href'] = "https://twitter.com/{$screenName}";
                break;
            case self::HASHTAG:
                $this->options['href'] = 'https://twitter.com/intent/tweet?button_hashtag=' . Html::encode($this->hashTag);
                $this->options['data-related'] = empty($this->options['data-related']) ? $screenName : $screenName . ',' . $this->options['data-related'];
                break;
            case self::MENTION:
                $this->options['href'] = "https://twitter.com/intent?screen_name={$screenName}";
                break;
            case self::TIMELINE:
                $this->options['href'] = "https://twitter.com/{$screenName}";
                $content = 'Tweets by @' . $this->screenName;
                if (isset($this->timelineConfig['listSlug'])) {
                    $slug = Html::encode($this->timelineConfig['listSlug']);
                    $this->options['href'] .= "/lists/{$slug}";
                    $this->options['data-list-slug'] = $slug;
                    if (!empty($this->options['data-list-owner-screen-name'])) {
                        $screenName = Html::encode($this->options['data-list-owner-screen-name']);
                    };
                    $this->options['data-list-owner-screen-name'] = $screenName;
                    $content = "Tweets from " . $this->options['href'];
                } elseif (isset($this->timelineConfig['search'])) {
                    $search = $this->timelineConfig['search'];
                    $isHash = substr($search, 0, 1) === '#';
                    if ($isHash) {
                        $search = substr($search, 1);
                    }
                    $search = Html::encode($search);
                    $this->options['href'] =  $isHash ? "https://twitter.com/hashtag/{$search}" : "https://twitter.com/search?q={$search}";
                    $content = $this->timelineConfig['search'] . ' Tweets';
                } elseif (isset($this->timelineConfig['collectionId'])) {
                    $id = Html::encode($this->timelineConfig['collectionId']);
                    $this->options['href'] .= "/timelines/{$id}";
                    if (empty($this->options['data-custom-timeline-id'])) {
                        $this->options['data-custom-timeline-id'] = $id;
                    }
                    $content = ArrayHelper::getValue($this->timelineConfig, 'collectionName', '');
                } else {
                    $this->options['data-screen-name'] = $screenName;
                }                    
                if (empty($this->content)) {
                    $this->content = $content;
                }
                break;
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