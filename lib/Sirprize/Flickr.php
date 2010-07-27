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


namespace Sirprize;


class Flickr
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
			#require_once 'Sirprize/Rest/Client.php';
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
		#require_once 'Sirprize/Flickr/Rest/ResponseHandler/Php.php';
		return new \Sirprize\Flickr\Rest\ResponseHandler\Php();
	}
	
	
	public function getCollectionsInstance()
	{
		#require_once 'Sirprize/Flickr/Collection/Collection.php';
		$collections = new \Sirprize\Flickr\Collection\Collection();
		$collections
			->setFlickr($this)
			->setRestClient($this->_getRestClient())
		;
		return $collections;
	}
	
	
	public function getPhotoSetsInstance()
	{
		#require_once 'Sirprize/Flickr/PhotoSet/Collection.php';
		$photoSets = new \Sirprize\Flickr\PhotoSet\Collection();
		$photoSets
			->setFlickr($this)
			->setRestClient($this->_getRestClient())
		;
		return $photoSets;
	}
	
	
	public function getPhotosInstance()
	{
		#require_once 'Sirprize/Flickr/Photo/Collection.php';
		$photo = new \Sirprize\Flickr\Photo\Collection();
		$photo
			->setFlickr($this)
			->setRestClient($this->_getRestClient())
		;
		return $photo;
	}
	
}