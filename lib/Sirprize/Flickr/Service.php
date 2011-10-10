<?php

/**
 * Flickr API Wrapper for PHP 5.3+ 
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt
 *
 * @category   Sirprize
 * @package    Flickr
 * @copyright  Copyright (c) 2010, Christian Hoegl, Switzerland (http://sirprize.me)
 * @license    MIT License
 */


namespace Sirprize\Flickr;


class Service
{
	
	
	protected $_restClient = null;
	protected $_apiKey = null;
	protected $_secret = null;
	
	
	public function __construct(array $config = array())
	{
		if(isset($config['apiKey']))
		{
			$this->_apiKey = $config['apiKey'];
		}
		
		if(isset($config['secret']))
		{
			$this->_secret = $config['secret'];
		}
	}
	
	
	public function setRestClient(\Sirprize\Rest\Client $restClient)
	{
		$this->_restClient = $restClient;
		return $this;
	}
	
	
	protected function _getRestClient()
    {
        if($this->_restClient === null)
		{
            $this->_restClient = new \Sirprize\Rest\Client();
        }
		
        return $this->_restClient;
    }
	
	
	public function setApiKey($apiKey)
	{
		$this->_apiKey = $apiKey;
		return $this;
	}
	
	
	public function getApiKey()
	{
		return $this->_apiKey;
	}
	
	
	public function setSecret($secret)
	{
		$this->_secret = $secret;
		return $this;
	}
	
	
	public function getSecret()
	{
		return $this->_secret;
	}
	
	
	public function getSignature(array $args)
	{
		$signature = $this->getSecret();
		
		foreach($args as $key => $val)
		{
			$signature .= $key.$val;
		}
		
		return md5($signature);
	}
	
	
	public function signArgs(array $args)
	{
		$args['api_sig'] = $this->getSignature($args);
		return $args;
	}
	
	
	public function getWebAuthUrl($permissions)
	{
		require_once 'Zend/Uri.php';
		$uri = \Zend_Uri::factory('http://flickr.com/services/auth/');
		
		$args = array(
			'api_key' => $this->getApiKey(),
			'perms' => $permissions
		);
		
		$uri->setQuery($this->signArgs($args));
		return $uri;
	}
	
	
	public function getResponseHandlerInstance()
	{
		return new \Sirprize\Flickr\Rest\ResponseHandler\Php();
	}
	
	
	public function getCollectionsInstance()
	{
		$collections = new \Sirprize\Flickr\Collection\Collection();
		$collections
			->setService($this)
			->setRestClient($this->_getRestClient())
		;
		return $collections;
	}
	
	
	public function getPhotoSetsInstance()
	{
		$photoSets = new \Sirprize\Flickr\PhotoSet\Collection();
		$photoSets
			->setService($this)
			->setRestClient($this->_getRestClient())
		;
		return $photoSets;
	}
	
	
	public function getPhotosInstance()
	{
		$photo = new \Sirprize\Flickr\Photo\Collection();
		$photo
			->setService($this)
			->setRestClient($this->_getRestClient())
		;
		return $photo;
	}
	
	
	public static function makeCacheIdFromParts($parts)
	{
		return preg_replace('/[^a-zA-Z0-9_]/', '_', implode('_', $parts));
	}
	
}