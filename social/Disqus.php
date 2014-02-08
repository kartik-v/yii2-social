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
 * Widget to embed Disqus comments on  your website
 * 
 * Usage:
 * ```
 * echo Disqus::widget([
 *     'settings' => ['shortname' => 'DISQUS_SHORTNAME']
 * ]);
 * ```
 * 
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Disqus extends SocialWidget {

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
     * @var boolean whether to display the comment count summary instead of the 
     * detailed Disqus standard comments widget
     */
    public $showCount = false;

    /**
     * @var string text for Disqus credits to be displayed at the end of the widget
     */
    public $credits;

    /**
     * Initialize the widget
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        $this->credits = Html::a(Yii::t('social', 'comments powered by Disqus'), 'http://disqus.com/?ref_noscript');
        $this->noscript = Yii::t('social', 'Please enable JavaScript to view the {pluginLink}.', ['pluginLink' => $this->credits]);
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
            'noscript' => $this->getNoScript()
        ];
        $view = ($this->showCount) ? 'disqus-count' : 'disqus-comments';
        echo $this->render($view, $params);
    }

}
