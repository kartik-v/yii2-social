<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */

namespace kartik\social;

use Facebook\PersistentData\PersistentDataInterface;
use Yii;

/**
 * Facebook persistent handler for Yii.
 */
class FacebookPersistentHandler implements PersistentDataInterface
{
    /**
     * @var string prefix to use for session variables.
     */
    public $sessionPrefix = 'FBPH_';

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return Yii::$app->session->get($this->sessionPrefix . $key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        Yii::$app->session->set($this->sessionPrefix . $key, $value);
    }
}