<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-markdown
 * @version 1.0.0
 */

namespace kartik\social;

/**
 * Facebook module wrapping the Facebook PHP SDK API for Yii Framework 2.0
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FacebookModule extends \yii\base\Module {

    /**
     * @var string the Facebook Application ID. 
     * This is mandatory.
     */
    public $appId;

    /**
     * @var string the Facebook Application Secret. 
     * This is mandatory.
     */
    public $secret;

    /**
     * @var boolean whether or not file uploads are enabled on your server
     * This is optional
     */
    public $fileUpload = false;

    /**
     * @var boolean whether or not to use signed_request data from query 
     * parameters or the POST body. For security purposes, this should be set 
     * to false for non-canvas apps.
     * This is optional.
     */
    public $allowSignedRequest = false;

    /**
     * @var object the Facebook API object 
     */
    private $_facebook;

    /**
     * Returns the Facebook SDK API object
     * @throws InvalidConfigException
     */
    public function getFbApi() {
        if (!isset($this->appId)) {
            throw new InvalidConfigException("The Facebook 'appId' has not been set.");
        }
        if (!isset($this->secret)) {
            throw new InvalidConfigException("The Facebook 'secret' has not been set.");
        }
        if (!isset($this->_facebook)) {
            $path = Yii::getPathAlias('@vendor/facebook/php-sdk/src/facebook.php');
            require_once($path);
            $config = [
                'appId' => $this->appId,
                'secret' => $this->secret,
                'fileUpload' => $this->fileUpload,
                'allowSignedRequest' => $this->allowSignedRequest
            ];
            $this->_facebook = new Facebook($config);
        }
        return $this->_facebook;
    }

    /**
     * Returns the Facebook User ID
     * @return string
     */
    public function getFbUser() {
        $facebook = $this->getFbApi();
        return $facebook->getUser();
    }

}
