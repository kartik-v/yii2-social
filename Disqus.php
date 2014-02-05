<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-social
 * @version 1.0.0
 */

namespace kartik\social;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * Widget to embed Disqus comments on  your website
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Disqus extends \yii\base\Widget {

    /**
     * @var array the Disqus settings
     * - shortname: string the disqus forum shortname
     * - identifier: string the disqus identifier for your page
     * - title: string the disqus title of the current page
     * - url: string the URL of the current page. If not set will be set to 
     *   the `window.location.href`
     * - category_id: string the category to be used for the current page. This 
     *   is used when creating the thread on Disqus for the first time.
     * - disable_mobile: boolean disable use of mobile optimized version of Disqus.
     */
    public $settings = [];

    /**
     * @throws CHttpException
     */
    public function init() {
        parent::init();
        if (empty($this->settings['shortname'])) {
            throw new InvalidConfigException("Disqus 'shortname' has not been set in `settings`.");
        }
        $variables = "";
        foreach ($this->settings as $key => $value) {
            $variables = ($key == 'disable_mobile') ?
                    "var discus_{$key} = {$value};\n" :
                    "var discus_{$key} = '{$value}';\n";
        }
        echo $this->render('disqus', ['variables' => $variables]);
    }

}
