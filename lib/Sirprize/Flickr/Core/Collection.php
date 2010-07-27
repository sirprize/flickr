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
class Collection extends \SplObjectStorage
{
	
	
	protected $_flickr = null;
	protected $_restClient = null;
	protected $_started = false;
	protected $_loaded = false;
	protected $_responseHandler = null;
	protected $_observers = array();
	
	
	
	
	public function setFlickr(\Sirprize\Flickr $flickr)
	{
		$this->_flickr = $flickr;
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
	 * @return \Sirprize\Flickr\PhotoSet\Collection
	 */
	public function attachObserver(\Sirprize\Flickr\Core\Collection\Observer $observer)
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
	 * @return \Sirprize\Flickr\PhotoSet\Collection
	 */
	public function detachObserver(\Sirprize\Flickr\Core\Collection\Observer $observer)
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
	
	
	
	protected function _getFlickr()
	{
		if($this->_flickr === null)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('call setFlickr() before '.__METHOD__);
		}
		
		return $this->_flickr;
	}
	
	
	
	protected function _getRestClient()
	{
		if($this->_restClient === null)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
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