<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2015
 * @package yii2-social
 * @version 1.3.1
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
        $this->_initVk = !in_array($this->type, [self::POST, self::COMMUNITY, self::SHARE, self::SUBSCRIBE]);
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
        if ($this->type === self::SHARE) {
            return $options;
        }
        $param = "'{$id}',{$options}";
        switch($this->type) {
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
                $param .= ", {$ownerId}";
                break;    
            case self::POST:
                $ownerId = ArrayHelper::getValue($this->settings, 'owner_id', 0);
                $postId = ArrayHelper::getValue($this->settings, 'post_id', 0);
                $hash = ArrayHelper::getValue($this->settings, 'hash', '');
                $param = "'{$id}', {$ownerId}, {$postId}, '{$hash}', {$options}";
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
            $view->registerCss('.vk_share td a{height:21px!important}');
            $view->registerJsFile('http://vk.com/js/api/share.js?91', ['charset'=>'windows-1251', 'position'=>View::POS_HEAD]);
            $js = "\$('#{$id}').html(VK.Share.button(false,{$params}));";
        } else {
            $view->registerJsFile('http://vk.com/js/api/openapi.js?116', ['position'=>View::POS_HEAD]);
            $w = ucfirst(substr($this->type, 3));
            if ($this->_initVk) { 
                $initOpts = Json::encode(['apiId' => $this->apiId, 'onlyWidgets' => true]);
                $view->registerJs("VK.init({$initOpts});", View::POS_HEAD);
            }
            $js = "VK.Widgets.{$w}({$params});";
        }
        $view->registerJs($js);
    }

}