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
class SocialWidget extends \yii\base\Widget {

    /**
     * @var string text to be displayed if browser does not support javascript.
     * if set to false will not displayed;
     */
    public $noscript;

    /**
     * @var string HTML attributes for the noscript message container
     */
    public $noscriptOptions = ['class' => 'alert alert-danger'];

    /**
     * @var array the the internalization configuration for this widget
     */
    public $i18n = [];

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
     * Generates the noscript container
     * @return string
     */
    protected function getNoScript() {
        if ($this->noscript == false) {
            return '';
        }
        return '<noscript>' . Html::tag('div', $this->noscript, $this->noscriptOptions) . '</noscript>';
    }

}
