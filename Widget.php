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
use kartik\base\Config;

/**
 * Base widget for all social widgets
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Widget extends \yii\base\Widget
{
    private static $defaultLanguage = [
        'facebook' => 'en_US',
        'twitter' => 'en',
        'google' => 'en-US'
    ];
    
    /**
     * @var string the language used in displaying content. 
     * If not provided, defaults to `en_US`.
     */
    public $language;

    /**
     * @var string the tag for enclosing the plugin. Defaults to 'div'.
     */
    public $tag = 'div';

    /**
     * @var string the HTML attributes for the plugin container.
     */
    public $options = [];

    /**
     * @var string the social plugin type
     */
    public $type;

    /**
     * @var string text to be prefixed for the data api
     */
    public $dataApiPrefix = 'data-';

    /**
     * @var array the social plugin settings
     */
    public $settings = [];

    /**
     * @var string the content to be embedded
     * in between the plugin tag
     */
    public $content = '';

    /**
     * @var string text to be displayed if browser does not support javascript.
     * If set to false will not displayed;
     */
    public $noscript;

    /**
     * @var array HTML attributes for the noscript message container
     */
    public $noscriptOptions = ['class' => 'alert alert-danger'];

    /**
     * @var array the the internalization configuration for this widget
     */
    public $i18n = [];

    /**
     * @var string the name of the module
     */
    public $moduleName = 'social';

    /**
     * @var array the valid plugins
     */
    protected $validPlugins = [];

    /**
     * Initialize the widget
     */
    public function init()
    {
        parent::init();
        if ($this->validPlugins !== false && empty($this->type)) {
            throw new InvalidConfigException("The plugin 'type' must be set.");
        }
        if ($this->validPlugins !== false && !in_array($this->type, $this->validPlugins)) {
            throw new InvalidConfigException("Invalid plugin type 'type'.");
        }
        Yii::setAlias('@kvsocial', dirname(__FILE__));
        if (empty($this->i18n)) {
            $this->i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvsocial/messages',
                'forceTranslation' => true
            ];
        }
        Yii::$app->i18n->translations['kvsocial'] = $this->i18n;
        if ($this->noscript !== false && empty($this->noscript)) {
            $this->noscript = Yii::t('kvsocial', 'You must enable Javascript on your browser for the site to work optimally and display sections completely.');
        }
    }

    /**
     * Gets configuration for a widget from the module
     *
     * @param string $widget name of the widget
     * @return array
     */
    public function getConfig($widget)
    {
        $module = Config::getModule($this->moduleName);
        return isset($module->$widget) ? $module->$widget : [];
    }

    /**
     * Sets configuration for a widget based on the module level configuration
     *
     * @param string $widget name of the widget
     */
    public function setConfig($widget)
    {
        $config = $this->getConfig($widget);
        if (empty($config)) {
            return;
        }
        foreach ($config as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                if ($key == 'settings') {
                    $this->settings = $this->settings + $value;
                } elseif (empty($this->$key)) {
                    $this->$key = $value;
                }
            }
        }
        if (empty($this->language) && isset(self::$defaultLanguage[$widget])) {
            $this->language = self::$defaultLanguage[$widget];
        }
    }

    /**
     * Generates the plugin markup
     *
     * @return string
     */
    protected function renderPlugin()
    {
        return Html::tag($this->tag, $this->content, $this->options) . "\n" . $this->renderNoScript();
    }

    /**
     * Generates the noscript container
     *
     * @return string
     */
    protected function renderNoScript()
    {
        if (empty($this->noscript)) {
            return '';
        }
        return '<noscript>' . Html::tag('div', $this->noscript, $this->noscriptOptions) . '</noscript>';
    }

    /**
     * Sets the options for the  plugin
     *
     * @param bool $convertLowerCase
     */
    protected function setPluginOptions($convertLowerCase = true)
    {
        Html::addCssClass($this->options, $this->type);
        foreach ($this->settings as $key => $value) {
            $key = str_replace($this->dataApiPrefix, "", $key);
            if ($convertLowerCase) {
                $key = strtolower($key);
            }
            $this->options[$this->dataApiPrefix . $key] = $value;
        }
    }
}
