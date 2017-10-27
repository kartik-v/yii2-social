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
use yii\helpers\Url;

/**
 * Widget to render various Github buttons. Based on the unofficial
 * Github Buttons by @mdo.
 *
 * Usage:
 * ```
 * echo GithubPlugin::widget([
 *     'type' => GithubPlugin::WATCH,
 *     'settings' => ['user' => 'GITHUB_USER', 'repo' => 'GITHUB_REPO']
 * ]);
 * ```
 *
 * @see http://ghbtns.com/
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class GithubPlugin extends Widget
{
    const WATCH = 'watch';
    const STAR = 'star';
    const FORK = 'fork';
    const FOLLOW = 'follow';
    const API = 'http://ghbtns.com/github-btn.html';

    /**
     * @var string the type of button. One of 'watch', 'fork', 'follow'. This is mandatory.
     */
    public $type;

    /**
     * @var array the social plugin settings. The following attributes are recognized:
     * - repo: string the Github repository name. This is mandatory.
     * - user: string the Github username that owns the repo. This is mandatory.
     * - count: boolean whether to display the watchers or forks count.
     * - size: the flag for using a larger button. The larger button option is 'large'.
     */
    public $settings = [];

    /**
     * @var string the HTML attributes for the plugin container.
     */
    public $options = [];

    /**
     * @var array the valid plugins
     */
    protected $validPlugins = [
        self::WATCH,
        self::STAR,
        self::FORK,
        self::FOLLOW
    ];

    /**
     * Initialize the widget
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->setConfig('github');
        $this->settings['type'] = $this->type;
        if (empty($this->settings['repo'])) {
            throw new InvalidConfigException("The GitHub 'repository' has not been set.");
        }
        if (empty($this->settings['user'])) {
            throw new InvalidConfigException("The GitHub 'user' must be set.");
        }
        if (empty($this->settings['type'])) {
            throw new InvalidConfigException("The GitHub button 'type' has not been set.");
        }
        if (!isset($this->noscript)) {
            $this->noscript = Yii::t('kvsocial', 'Please enable JavaScript on your browser to view the Facebook {pluginName} plugin correctly on this site.', ['pluginName' => Yii::t('kvsocial', str_replace('fb-', '', $this->type))]
            );
        }
        if (!empty($this->settings['count']) && ($this->settings['count'] || $this->settings['count'] == 'true')) {
            $this->settings['count'] = 'true';
        } else {
            unset($this->settings['count']);
        }
        $this->settings['v'] = 2;
        $large = (!empty($this->settings['size']) && $this->settings['size'] == 'large');
        $defaultOptions = ['allowtransparency' => "true", 'frameborder' => 0, 'scrolling' => 0] +
            ($large ? ['width' => 170, 'height' => 30] : ['width' => 110, 'height' => 20]);
        $this->options = array_replace($defaultOptions, $this->options);
        $this->options['src'] = ltrim(Url::to([self::API] + $this->settings), Url::to(['/']));
        echo Html::tag('iframe', '', $this->options);
    }
}