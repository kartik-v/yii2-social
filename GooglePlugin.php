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
use yii\helpers\Json;
use yii\web\View;

/**
 * Widget to render various Google plugins
 *
 * Usage:
 * ```
 * echo GooglePlugin::widget([
 *     'type' => GooglePlugin::SHARE
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class GooglePlugin extends Widget
{
    const SIGNIN = 'g-signin';
    const PLUS_ONE = 'g-plusone';
    const SHARE = 'g-plus';
    const FOLLOW = 'g-follow';
    const BADGE_PAGE = 'g-page';
    const BADGE_PERSON = 'g-person';
    const BADGE_COMMUNITY = 'g-community';
    const HANGOUT = 'g-hangout';
    const POST = 'g-post';

    /**
     * @var string the Google plugin type
     * defaults to Google Plus One
     */
    public $type = self::PLUS_ONE;

    /**
     * @var string the Google Plus Client ID.
     */
    public $clientId;

    /**
     * @var string the Google Plus Profile ID.
     */
    public $profileId;

    /**
     * @var string the Google Page ID.
     */
    public $pageId;

    /**
     * @var string the Google Plus Community ID.
     */
    public $communityId;

    /**
     * @var array the HTML attributes for the signin container
     */
    public $signinOptions = [];

    /**
     * Initialize the widget
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->validPlugins = [
            self::SIGNIN,
            self::PLUS_ONE,
            self::SHARE,
            self::FOLLOW,
            self::BADGE_PAGE,
            self::BADGE_PERSON,
            self::BADGE_COMMUNITY,
            self::HANGOUT,
            self::POST,
        ];
        parent::init();
        $this->setConfig('google');
        if ($this->type === self::SIGNIN && empty($this->clientId) && empty($this->options['data-clientid'])) {
            throw new InvalidConfigException("The Google 'clientId' must be set for the signin button.");
        }
        if ($this->type === self::FOLLOW && empty($this->pageId) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Google 'pageId' must be set for the follow button.");
        }
        if ($this->type === self::BADGE_PAGE && empty($this->pageId) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Google 'pageId' must be set for the page badge.");
        }
        if ($this->type === self::BADGE_PERSON && empty($this->profileId) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Google 'profileId' must be set for the person badge.");
        }
        if ($this->type === self::BADGE_COMMUNITY && empty($this->communityId) && empty($this->options['data-href'])) {
            throw new InvalidConfigException("The Google 'communityId' must be set for the community badge.");
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('kvsocial', 'Please enable JavaScript on your browser to view the Google {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('kvsocial', str_replace('ga-', '', $this->type))]
            );
        }
        $this->registerAssets();
        $this->setPluginOptions();
        $content = Html::tag($this->tag, '', $this->options);
        if ($this->type === self::SIGNIN) {
            $content = Html::tag($this->tag, $content, $this->signinOptions);
        }
        echo $content . "\n" . $this->renderNoScript();
    }

    /**
     * Sets the options for the Google plugin
     */
    protected function setPluginOptions($convertLowerCase = true)
    {
        parent::setPluginOptions();
        if ($this->type === self::SIGNIN && empty($this->options["data-clientid"])) {
            $this->options["data-clientid"] = $this->clientId;
        } elseif ($this->type === self::SHARE && empty($this->options["data-action"])) {
            $this->options["data-action"] = 'share';
        } elseif ($this->type === self::BADGE_PERSON && empty($this->options["data-href"])) {
            $this->options["data-href"] = "https://plus.google.com/{$this->profileId}";
        } elseif ($this->type === self::BADGE_COMMUNITY && empty($this->options["data-href"])) {
            $this->options["data-href"] = "https://plus.google.com/communities/{$this->communityId}";
        } elseif (($this->type === self::FOLLOW || $this->type === self::BADGE_PAGE) && empty($this->options["data-href"])) {
            $this->options["data-href"] = "https://plus.google.com/{$this->pageId}";
        } elseif ($this->type === self::HANGOUT && empty($this->options["data-render"])) {
            $this->options["data-render"] = 'createhangout';
        }
    }

    /**
     * Registers the necessary assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        $view->registerJsFile('https://apis.google.com/js/platform.js', [
            'position' => View::POS_HEAD, 
            'async' => true, 
            'defer' => true
        ]);
        $view->registerJs("\nwindow.___gcfg={lang:'{$this->language}'};\n", View::POS_HEAD);
    }
}