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
class GooglePlugin extends Widget {

    const SIGNIN = 'g-signin';
    const PLUSONE = 'g-plusone';
    const SHARE = 'g-plus';
    const BADGE_PAGE = 'g-page';
    const BADGE_PERSON = 'g-person';
    const BADGE_PERSON = 'g-community';
    const FOLLOW = 'g-follow';
    const HANGOUT = 'g-hangout';
    const INTERACTIVE_POST = 'g-interactivepost';

    /**
     * @var string the Google Plus Client ID.
     */
    public $clientId;

    /**
     * @var string the Google Page ID.
     */
    public $pageId;

    /**
     * @var string the Google plugin type
     * defaults to Google Comments
     */
    public $type = self::COMMENT;

    /**
     * @var array the Google plugin settings
     */
    public $settings = [];

    /**
     * @var array list of plugins that use [[pageId]]
     */
    private static $_pagePlugins = [
        self::BADGE_PERSON,
        self::FOLLOW,
    ];

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        $this->setConfig('google');
        $settings = ['class' => $this->type];
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('social', 'Please enable JavaScript on your browser to view the Google {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('social', str_replace('fb-', '', $this->type))]
            );
        }
        foreach ($this->settings as $key => $value) {
            $settings["data_{$key}"] = $value;
        }
        if (in_array($this->type, self::_pagePlugins)) {
            $settings["data_href"] = "https://plus.google.com/{$this->pageId}";
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
(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
SCRIPT;
        $view->registerJs($js);
    }

}
