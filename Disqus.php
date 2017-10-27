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
class Disqus extends Widget
{
    /**
     * @var boolean whether to display the comment count summary instead of the
     * detailed Disqus standard comments widget
     */
    public $showCount = false;

    /**
     * @var boolean whether to display the credits text after the widget itself.
     * Credits text will be used in {@link noscript} block regardless of this setting.
     */
    public $showCredits = true;

    /**
     * @var string text for Disqus credits to be displayed at the end of the widget
     */
    public $credits;

    /**
     * Initialize the widget
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->validPlugins = false;
        parent::init();
        $config = $this->setConfig('disqus');
        if ($this->credits === null) {
            $this->credits = Html::a(Yii::t('kvsocial', 'comments powered by Disqus'), 'http://disqus.com/?ref_noscript');
        }
        $this->noscript = Yii::t('kvsocial', 'Please enable JavaScript to view the {pluginLink}.', ['pluginLink' => $this->credits]);
        if (empty($this->settings['shortname'])) {
            throw new InvalidConfigException("Disqus 'shortname' has not been set in settings.");
        }
        $variables = "var disqus_config = function() {\n";
		$shortname = ArrayHelper::remove($this->settings, 'shortname', '');
        foreach ($this->settings as $key => $value) {
			$variables .= "\t\t\tthis.page.{$key} = " . (($key == 'disable_mobile') ? $value : "'{$value}';\n");
        }
		$variables .= "\t\t};\n";
        $params = [
			'shortname' => $shortname,
            'variables' => $variables,
            'credits' => $this->showCredits ? $this->credits : '',
            'noscript' => $this->renderNoScript()
        ];
        $view = ($this->showCount) ? 'disqus-count' : 'disqus-comments';
        echo $this->render($view, $params);
    }
}