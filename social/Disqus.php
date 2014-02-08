<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-social
 * @version 1.0.0
 */

namespace kartik\social;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

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
     *
     * @var boolean whether to display the comment count summary instead of the 
     * detailed Disqus standard comments widget
     */
    public $showCount = false;

    /**
     * @var string text to be displayed if browser does not support javascript 
     */
    public $noscript = 'Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a>';

    /**
     * @var string HTML attributes for the noscript message container
     */
    public $noscriptOptions = ['class' => 'alert alert-danger'];    

    /**
     * @var string text for Disqus credits to be displayed at the end of the widget
     */
    public $credits = '<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>';

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        if (empty($this->settings['shortname'])) {
            throw new InvalidConfigException("Disqus 'shortname' has not been set in `settings`.");
        }
        $variables = "";
        foreach ($this->settings as $key => $value) {
            $variables .= ($key == 'disable_mobile') ?
                    "var disqus_{$key} = {$value};\n" :
                    "var disqus_{$key} = '{$value}';\n";
        }
        $params = [
            'variables' => $variables, 
            'credits' => $this->credits, 
            'noscript' => Html::tag('div', $this->noscript, $this->noscriptOptions)
        ];
        $view = ($this->showCount) ? 'disqus-count' : 'disqus-comments';
        echo $this->render($view, $params);
    }

}
