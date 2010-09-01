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


namespace Sirprize\Flickr\Photo;


#require_once 'Sirprize/Flickr/Core/Collection.php';


/**
 * Encapsulate a set of persisted photo objects and the operations performed over them
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Collection extends \Sirprize\Flickr\Core\Collection
{
	
	
	/**
	 * Instantiate a new photo entity
	 *
	 * @return \Sirprize\Flickr\Photo\Entity
	 */
	public function getPhotoInstance()
	{
		#require_once 'Sirprize/Flickr/Photo/Entity.php';
		$photo = new \Sirprize\Flickr\Photo\Entity();
		$photo
			->setRestClient($this->_getRestClient())
			->setFlickr($this->_getFlickr())
		;
		
		return $photo;
	}
	
	
	
	/**
	 * Defined by \SplObjectStorage
	 *
	 * Add photo entity
	 *
	 * @param \Sirprize\Flickr\Photo\Entity $photo
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Photo\Collection
	 */
	public function attach($photo, $data = null)
	{
		if(!$photo instanceof \Sirprize\Flickr\Photo\Entity)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('expecting an instance of \Sirprize\Flickr\Photo\Entity');
		}
		
		parent::attach($photo);
		return $this;
	}
	
	
	
	
	/**
	 * Fetch photos
	 *
	 * @param string $photoSetId
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Photo\Collection
	 */
	public function startByPhotoSetId(\Sirprize\Flickr\Id $photoSetId)
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
			'method' => 'flickr.photosets.getPhotos',
			'photoset_id' => (string) $photoSetId
		);
		
		try {
			$this->_getRestClient()
				->getHttpClient()
				->resetParameters()
				->setUri($uri)
				->setParameterGet($args)
			;
			
			$cacheId = $this->_getRestClient()->makeCacheIdFromParts(array(__METHOD__, $photoSetId));
			$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			$this->_getRestClient()->get($this->_responseHandler, 2, array(), $cacheId);
			/*
			$this->_responseHandler = $this->_getFlickr()->getResponseHandlerInstance();
			
			$this->_getRestClient()
				->setCacheIdFromParts(array(__METHOD__, $photoSetId))
				->setResponseHandler($this->_responseHandler)
				->setUri($uri)
				->get($args)
			;
			*/
			if($this->_responseHandler->isError())
			{
				// service error
				$this->_onStartError($this->_getOnStartErrorMessage($this->_responseHandler->getMessage()));
				return $this;
			}
			
			$data = $this->_responseHandler->getPhp();
			
			foreach($data['photoset']['photo'] as $item)
			{
				$photo = $this->getPhotoInstance();
				$photo->load($item);
				$this->attach($photo);
			}
			
			$this->_onStartSuccess($this->_getOnStartSuccessMessage());
			$this->_loaded = true;
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
	
	
	
	public function getById(\Sirprize\Flickr\Id $id)
	{
		if(!$this->_started)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('collection must be started before calling '.__METHOD__);
		}
		
		foreach($this as $photo)
		{
			if((string) $photo->getId() == (string) $id)
			{
				return $photo;
			}
		}
		
		return null;
	}
	
	
	
	protected function _getOnStartSuccessMessage()
	{
		return "started photo collection. found ".$this->count()." photos";
	}
	
	
	
	protected function _getOnStartErrorMessage($errorMessage)
	{
		return "photo collection could not be started: '".$errorMessage."'";
	}
}