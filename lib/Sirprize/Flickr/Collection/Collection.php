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


namespace Sirprize\Flickr\Collection;


require_once 'Sirprize/Flickr/Core/Collection.php';


/**
 * Encapsulate a set of persisted collection objects and the operations performed over them
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Collection extends \Sirprize\Flickr\Core\Collection
{
	
	
	/**
	 * Instantiate a new collection entity
	 *
	 * @return \Sirprize\Flickr\Collection\Entity
	 */
	public function getCollectionInstance()
	{
		require_once 'Sirprize/Flickr/Collection/Entity.php';
		$collection = new \Sirprize\Flickr\Collection\Entity();
		$collection
			->setRestClient($this->_getRestClient())
			->setFlickr($this->_getFlickr())
		;
		
		return $collection;
	}
	
	
	
	/**
	 * Defined by \SplObjectStorage
	 *
	 * Add collection entity
	 *
	 * @param \Sirprize\Flickr\Collection\Entity $collection
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Collection\Collection
	 */
	public function attach($collection, $data = null)
	{
		if(!$collection instanceof \Sirprize\Flickr\Collection\Entity)
		{
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('expecting an instance of \Sirprize\Flickr\Collection\Entity');
		}
		
		parent::attach($collection);
		return $this;
	}
	
	
	
	/**
	 * Fetch collections
	 *
	 * @param \Sirprize\Flickr\Id $userId The user to get a collection list for
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Collection\Collection
	 */
	public function startAllByUserId(\Sirprize\Flickr\Id $userId)
	{
		if($this->_started)
		{
			return $this;
		}
		
		$this->_started = true;
		
		require_once 'Zend/Uri.php';
		$uri = \Zend_Uri::factory('http://api.flickr.com/services/rest/');
		
		$args = array(
			'api_key' => $this->_getFlickr()->getApiKey(),
			'format' => 'php_serial',
			'method' => 'flickr.collections.getTree',
			'user_id' => (string) $userId
		);
		
		try {
		 	$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			
			$this->_getRestClient()
				->setCacheIdFromParts(array(__METHOD__, $userId))
				->setResponseHandler($this->_responseHandler)
				->setUri($uri)
				->get($args)
			;
			
			if($this->_responseHandler->isError())
			{
				// service error
				$this->_onStartError($this->_getOnStartErrorMessage($this->_responseHandler->getErrorMessage()));
				return $this;
			}
			
			$this->load($this->_responseHandler->getPhp());
			$this->_onStartSuccess($this->_getOnStartSuccessMessage());
			return $this;
		}
		catch(Exception $e)
		{
			// connection error
			$this->_onStartError($this->_getOnStartErrorMessage($e->getMessage()));
			
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception($exception->getMessage());
		}
	}
	
	
	
	
	/**
	 * Fetch collection
	 *
	 * @param \Sirprize\Flickr\Id $id
	 * @param \Sirprize\Flickr\Id $userId The user to get a collection for
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Collection\Entity
	 */
	public function startByIdAndUserId(\Sirprize\Flickr\Id $id, \Sirprize\Flickr\Id $userId)
	{
		if($this->_started)
		{
			return $this;
		}
		
		$this->_started = true;
		
		require_once 'Zend/Uri.php';
		$uri = \Zend_Uri::factory('http://api.flickr.com/services/rest/');
		
		$args = array(
			'api_key' => $this->_getFlickr()->getApiKey(),
			'format' => 'php_serial',
			'method' => 'flickr.collections.getTree',
			'collection_id' => (string) $id,
			'user_id' => (string) $userId
		);
		
		try {
		 	$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			
			$this->_getRestClient()
				->setCacheIdFromParts(array(__METHOD__, $id))
				->setResponseHandler($this->_responseHandler)
				->setUri($uri)
				->get($args)
			;
			
			if($this->_responseHandler->isError())
			{
				// service error
				$this->_onStartError($this->_getOnStartErrorMessage($this->_responseHandler->getErrorMessage()));
				return null;
			}
			
			$this->load($this->_responseHandler->getPhp());
			$this->_onStartSuccess($this->_getOnStartSuccessMessage());
			$this->rewind();
			return $this->current();
		}
		catch(Exception $e)
		{
			// connection error
			$this->_onStartError($this->_getOnStartErrorMessage($e->getMessage()));
			
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception($exception->getMessage());
		}
	}
	
	
	
	/**
	 * Instantiate collection objects with api response data
	 *
	 * @return \Sirprize\Flickr\Collection\Collection
	 */
	public function load(array $data)
	{
		if($this->_loaded)
		{
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('collection has already been loaded');
		}
		
		foreach($data['collections']['collection'] as $item)
		{
			$collection = $this->getCollectionInstance();
			$collection->load($item);
			$this->attach($collection);
		}
		
		$this->_loaded = true;
		return $this;
	}
	
	
	
	protected function _getOnStartSuccessMessage()
	{
		return "started collection collection. found ".$this->count()." collections";
	}
	
	
	
	protected function _getOnStartErrorMessage($errorMessage)
	{
		return "collection collection could not be started: '".$errorMessage."'";
	}
	
}