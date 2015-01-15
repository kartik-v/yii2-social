<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-social
 * @version 1.3.0
 */

namespace kartik\social;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Extended FacebookRedirectLoginHelperX class
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FacebookRedirectLoginHelperX extends \Facebook\FacebookRedirectLoginHelper
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