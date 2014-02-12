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
 * Base widget for all social widgets
 * 
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Widget extends \yii\base\Widget {

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
     * Initialize the widget
     */
    public function init() {
        parent::init();
        Yii::setAlias('@social', dirname(__FILE__));
        if (empty($this->i18n)) {
            $this->i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@social/messages',
                'forceTranslation' => true
            ];
        }
        Yii::$app->i18n->translations['social'] = $this->i18n;
        $this->noscript = Yii::t('social', 'You must enable Javascript on your browser for the site to work optimally and display sections completely.');
    }

    /**
     * Gets configuration for a widget from the module
     * @param string $widget name of the widget
     * @return array
     */
    public function getConfig($widget) {
        $module = Yii::$app->getModule($this->moduleName);
        return isset($module->$widget) ? $module->$widget : [];
    }

    /**
     * Sets configuration for a widget based on the module level configuration
     * @param string $widget name of the widget
     */
    public function setConfig($widget) {
        $config = $this->getConfig($widget);
        if (empty($config)) {
            return;
        }
        foreach ($config as $key => $value) {
            if (empty($this->$key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Generates the plugin markup
     * @return string
     */
    protected function renderPlugin() {
        return Html::tag($this->tag, '', $this->options) . "\n" . $this->renderNoScript();
    }
    
    /**
     * Generates the noscript container
     * @return string
     */
    protected function renderNoScript() {
        if ($this->noscript == false) {
            return '';
        }
        return '<noscript>' . Html::tag('div', $this->noscript, $this->noscriptOptions) . '</noscript>';
    }

    /**
     * Sets the options for the  plugin
     */
    protected function setPluginOptions() {
        $this->options = ['class' => $this->type];
        foreach ($this->settings as $key => $value) {
            $key = str_replace($this->dataApiPrefix, "", $key);
            $this->options[$this->dataApiPrefix . $key] = $value;
        }
    }

}
