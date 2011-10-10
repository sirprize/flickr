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


namespace Sirprize\Flickr\Core;


/**
 * @category  Sirprize
 * @package   Flickr
 */
class Entity
{
	
	
	protected $_service = null;
	protected $_restClient = null;
	protected $_started = false;
	protected $_loaded = false;
	protected $_responseHandler = null;
	protected $_observers = array();
	
	
	public function setService(\Sirprize\Flickr\Service $service)
	{
		$this->_service = $service;
		return $this;
	}
	
	
	public function setRestClient(\Sirprize\Rest\Client $restClient)
	{
		$this->_restClient = $restClient;
		return $this;
	}
	
	
	/**
	 * Get response object
	 *
	 * @return \Sirprize\Flickr\Response|null
	 */
	public function getResponseHandler()
	{
		return $this->_responseHandler;
	}
	
	
	/**
	 * Attach observer object
	 *
	 * @return \Sirprize\Flickr\Core\Entity
	 */
	public function attachObserver(\Sirprize\Flickr\Core\Entity\Observer\Abstrakt $observer)
	{
		$exists = false;
		
		foreach(array_keys($this->_observers) as $key)
		{
			if($observer === $this->_observers[$key])
			{
				$exists = true;
				break;
			}
		}
		
		if(!$exists)
		{
			$this->_observers[] = $observer;
		}
		
		return $this;
	}
	
	
	/**
	 * Detach observer object
	 *
	 * @return \Sirprize\Flickr\Core\Entity
	 */
	public function detachObserver(\Sirprize\Flickr\Core\Entity\Observer\Abstrakt $observer)
	{
		foreach(array_keys($this->_observers) as $key)
		{
			if($observer === $this->_observers[$key])
			{
				unset($this->_observers[$key]);
				break;
			}
		}
		
		return $this;
	}
	
	
	
	protected function _getService()
	{
		if($this->_service === null)
		{
			throw new \Sirprize\Flickr\Exception('call setService() before '.__METHOD__);
		}
		
		return $this->_service;
	}
	
	
	
	protected function _getRestClient()
	{
		if($this->_restClient === null)
		{
			throw new \Sirprize\Flickr\Exception('call setRestClient() before '.__METHOD__);
		}
		
		return $this->_restClient;
	}
	
	
	
	protected function _onStartSuccess($message = null)
	{
		foreach($this->_observers as $observer)
		{
			$observer->onStartSuccess($this, $message);
		}
	}
	
	
	
	protected function _onStartError($message = null)
	{
		foreach($this->_observers as $observer)
		{
			$observer->onStartError($this, $message);
		}
	}
	
}