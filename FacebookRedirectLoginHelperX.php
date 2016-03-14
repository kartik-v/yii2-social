<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2016
 * @package yii2-social
 * @version 1.3.2
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use \Facebook\Helpers\FacebookRedirectLoginHelper;

/**
 * Extended FacebookRedirectLoginHelperX class
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FacebookRedirectLoginHelperX extends FacebookRedirectLoginHelper
{
    /**
     * @inheritdoc
     */
    protected function storeState($state)
    {
        Yii::$app->session->set('state', $state);
    }

    /**
     * @inheritdoc
     */
    protected function loadState()
    {
        return Yii::$app->session->get('state');
    }
}