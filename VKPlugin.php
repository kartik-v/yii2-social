<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Widget to render various VKontakte plugins
 *
 * Usage:
 * ```
 * echo VKPlugin::widget([
 *     'apiId' => 'FACEBOOK_APP_ID',
 *     'type' => VKPlugin::COMMENT,
 *     'settings' => ['colorscheme' => 'dark']
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class VKPlugin extends Widget
{
    const COMMENTS = 'vk_comments';
    const POST = 'vk_post';
    const COMMUNITY = 'vk_group';
    const LIKE = 'vk_like';
    const RECO = 'vk_recommended';
    const POLL = 'vk_poll';
    const AUTH = 'vk_auth';
    const SHARE = 'vk_share';
    const SUBSCRIBE = 'vk_subscribe';

    /**
     * @var string the VKontakte Application ID. This is mandatory.
     */
    public $apiId;

    /**
     * @var array the additional
     */
    public $config;

    /**
     * @var string the VKontakte plugin type
     * defaults to VKontakte Comments
     */
    public $type = self::COMMENTS;

    /**
     * @var array the VKontakte plugin settings that vary based on type.
     */
    public $settings = [];

    /**
     * @var array the cached options
     */
    protected $_options = [];

    /**
     * @var bool whether to initialize VK with the `$apiId`
     */
    protected $_initVk = true;

    /**
     * Initialize the widget
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        static $initDone = false;

        $this->validPlugins = [
            self::COMMENTS,
            self::POST,
            self::COMMUNITY,
            self::LIKE,
            self::RECO,
            self::POLL,
            self::AUTH,
            self::SHARE,
            self::SUBSCRIBE,
        ];
        parent::init();
        $this->setConfig('vk');

        $initRequired  = !in_array($this->type, [self::POST, self::COMMUNITY, self::SHARE, self::SUBSCRIBE]);
        $this->_initVk = $initRequired && !$initDone;
        if ($this->_initVk) {
            $initDone = true;
        }

        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (empty($this->type)) {
            throw new InvalidConfigException("The plugin 'type' must be set.");
        }
        if (empty($this->apiId) && $this->_initVk) {
            throw new InvalidConfigException("The VKontakte 'apiId' has not been set.");
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('kvsocial', 'Please enable JavaScript on your browser to view the VKontakte {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('kvsocial', str_replace('vk_', '', $this->type))]
            );
        }
        $this->_options = $this->options;
        $this->options = ['id' => $this->options['id'], 'class' => $this->type];
        unset($this->_options['id']);
        $this->registerAssets();
        echo $this->renderPlugin();
    }

    /**
     * Gets the plugin parameter settings for JS code
     * @return string
     */
    protected function getPluginParams()
    {
        $id = $this->options['id'];
        $options = Json::encode($this->_options);
        $param = "'$id', $options";
        switch ($this->type) {
            case self::SHARE:
                $param = '';
                if (!empty($this->_options)) {
                    $shareOpts = $this->_options;
                    $buttonOpts = [];
                    if (isset($shareOpts['type'])) {
                        $buttonOpts['type'] = $shareOpts['type'];
                        unset($shareOpts['type']);
                    }
                    if (isset($shareOpts['text'])) {
                        $buttonOpts['text'] = $shareOpts['text'];
                        unset($shareOpts['text']);
                    }
                    $param = Json::encode($shareOpts) . ', ' . Json::encode($buttonOpts);
                }
                break;
            case self::LIKE:
                if (isset($this->settings['page_id'])) {
                    $param .= ', ' . $this->settings['page_id'];
                }
                break;
            case self::POLL:
                if (isset($this->settings['poll_id'])) {
                    $param .= ", '" . $this->settings['poll_id'] . "'";
                }
                break;
            case self::COMMUNITY:
                if (isset($this->settings['group_id'])) {
                    $param .= ', ' . $this->settings['group_id'];
                }
                break;
            case self::SUBSCRIBE:
                $ownerId = ArrayHelper::getValue($this->settings, 'owner_id', 0);
                $param .= ", $ownerId";
                break;
            case self::POST:
                $ownerId = ArrayHelper::getValue($this->settings, 'owner_id', 0);
                $postId = ArrayHelper::getValue($this->settings, 'post_id', 0);
                $hash = ArrayHelper::getValue($this->settings, 'hash', '');
                $param = "'$id', $ownerId, $postId, '$hash', $options";
                break;
        }
        return $param;
    }

    /**
     * Registers the necessary assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        $params = $this->getPluginParams();
        $id = $this->options['id'];
        if ($this->type === self::SHARE) {
            $script   = 'share.js?91';
            $scriptId = 'vk_share_script_tag';
            $check    = 'VK.Share';
            $call     = "\$('#$id').html(VK.Share.button($params))";
        } else {
            $script   = 'openapi.js?116';
            $scriptId = 'vk_openapi_script_tag';
            $check    = 'VK.Widgets';
            $widget   = ucfirst(substr($this->type, 3));
            $call     = "VK.Widgets.$widget($params)";
            if ($this->_initVk) {
                $initOpts = Json::encode(['apiId' => $this->apiId, 'onlyWidgets' => true]);
                $js = "VK.init({$initOpts});";
                $view->registerJs($js);
            }
        }

        $view->registerJsFile('//vk.com/js/api/' . $script, ['async'=>true, 'id'=>$scriptId, 'position'=>View::POS_HEAD]);
        $js = <<< SCRIPT
if (window.VK && $check) {
    $call;
} else {
    \$('#$scriptId').load(function() {
        $call;
    });
}
SCRIPT;
        $view->registerJs($js);
    }
}
