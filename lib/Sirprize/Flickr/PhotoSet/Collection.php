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


namespace Sirprize\Flickr\PhotoSet;


#require_once 'Sirprize/Flickr/Core/Collection.php';


/**
 * Encapsulate a set of persisted photoSet objects and the operations performed over them
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Collection extends \Sirprize\Flickr\Core\Collection
{
	
	
	/**
	 * Instantiate a new photoSet entity
	 *
	 * @return \Sirprize\Flickr\PhotoSet\Entity
	 */
	public function getPhotoSetInstance()
	{
		#require_once 'Sirprize/Flickr/PhotoSet/Entity.php';
		$photoSet = new \Sirprize\Flickr\PhotoSet\Entity();
		$photoSet
			->setRestClient($this->_getRestClient())
			->setFlickr($this->_getFlickr())
		;
		
		return $photoSet;
	}
	
	
	
	/**
	 * Defined by \SplObjectStorage
	 *
	 * Add photoSet entity
	 *
	 * @param \Sirprize\Flickr\PhotoSet\Entity $photoSet
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\PhotoSet\Collection
	 */
	public function attach($photoSet, $data = null)
	{
		if(!$photoSet instanceof \Sirprize\Flickr\PhotoSet\Entity)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('expecting an instance of \Sirprize\Flickr\PhotoSet\Entity');
		}
		
		parent::attach($photoSet);
		return $this;
	}
	
	
	
	/**
	 * Fetch photoSets
	 *
	 * @param \Sirprize\Flickr\Id $userId The user to get a photoSet list for
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\PhotoSet\Collection
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
			'method' => 'flickr.photosets.getList',
			'user_id' => (string) $userId
		);
		
		try {
			$this->_getRestClient()
				->getHttpClient()
				->resetParameters()
				->setUri($uri)
				->setParameterGet($args)
			;
			
			$cacheId = $this->_getRestClient()->makeCacheIdFromParts(array(__METHOD__, $userId));
			$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			$this->_getRestClient()->get($this->_responseHandler, 2, array(), $cacheId);
			
			/*
		 	$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			
			$this->_getRestClient()
				->setCacheIdFromParts(array(__METHOD__, $userId))
				->setResponseHandler($this->_responseHandler)
				->setUri($uri)
				->get($args)
			;
			*/
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
			
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception($exception->getMessage());
		}
	}
	
	
	
	/**
	 * Instantiate photoSet objects with api response data
	 *
	 * @return \Sirprize\Flickr\PhotoSet\Collection
	 */
	public function load(array $data)
	{
		if($this->_loaded)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('collection has already been loaded');
		}
		
		foreach($data['photosets']['photoset'] as $item)
		{
			$photoSet = $this->getPhotoSetInstance();
			$photoSet->load($item);
			$this->attach($photoSet);
		}
		
		$this->_loaded = true;
		return $this;
	}
	
	
	
	public function getById(\Sirprize\Flickr\Id $id)
	{
		if(!$this->_started)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('collection must be started before calling '.__METHOD__);
		}
		
		foreach($this as $photoSet)
		{
			if((string) $photoSet->getId() == (string) $id)
			{
				return $photoSet;
			}
		}
		
		return null;
	}
	
	
	
	protected function _getOnStartSuccessMessage()
	{
		return "started photoSet collection. found ".$this->count()." photoSets";
	}
	
	
	
	protected function _getOnStartErrorMessage($errorMessage)
	{
		return "photoSet collection could not be started: '".$errorMessage."'";
	}
	
}