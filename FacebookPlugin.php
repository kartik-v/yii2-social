<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;

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
class FacebookPlugin extends Widget
{
    const LIKE = 'fb-like';
    const SHARE = 'fb-share-button';
    const SEND = 'fb-send';
    const SAVE = 'fb-save';
    const POST = 'fb-post';
    const VIDEO = 'fb-video';
    const FOLLOW = 'fb-follow';
    const COMMENT = 'fb-comments';
    const PAGE = 'fb-page';

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
     * @var bool whether to load facebook JS asynchronously.
     * Defaults to `true`.
     */
    public $async = true;

    /**
     * @var array the Facebook plugin settings
     */
    public $settings = [];

    /**
     * @inheritdoc
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->validPlugins = [
            self::LIKE,
            self::SHARE,
            self::SEND,
            self::SAVE,
            self::POST,
            self::VIDEO,
            self::FOLLOW,
            self::COMMENT,
            self::PAGE,
        ];
        parent::init();
        $this->setConfig('facebook');
        if (empty($this->type)) {
            throw new InvalidConfigException("The plugin 'type' must be set.");
        }
        if (empty($this->appId)) {
            throw new InvalidConfigException("The Facebook 'appId' has not been set.");
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t(
                'kvsocial',
                'Please enable JavaScript on your browser to view the Facebook {pluginName} plugin correctly on this site.',
                ['pluginName' => Yii::t('kvsocial', str_replace('fb-', '', $this->type))]
            );
        }
        $this->registerAssets();
        if ($this->type === self::COMMENT && empty($this->settings['data-href'])) {
            $this->settings['data-href'] = Yii::$app->request->getAbsoluteUrl();
        }
        $this->setPluginOptions();
        echo "<div id='fb-root'></div>\n" . $this->renderPlugin();
    }

    /**
     * Registers the necessary assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        $async = $this->async ? "js.async = true;" : "";
        $lang = str_replace('-', '_', $this->language);
        $js = <<< SCRIPT
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    {$async}
    js.src = "//connect.facebook.net/{$lang}/sdk.js#xfbml=1&appId={$this->appId}&version=v2.0";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));                
SCRIPT;
        $view->registerJs($js);
    }
}
