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


#require_once 'Sirprize/Flickr/Core/Entity.php';


/**
 * Represent and modify an entity
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Sizes extends \Sirprize\Flickr\Core\Entity
{
	
	protected $_squareWidth = null;
	protected $_squareHeight = null;
	protected $_squareSource = null;
	protected $_squareUrl = null;
	protected $_squareMedia = null;
	
	protected $_thumbnailWidth = null;
	protected $_thumbnailHeight = null;
	protected $_thumbnailSource = null;
	protected $_thumbnailUrl = null;
	protected $_thumbnailMedia = null;
	
	protected $_smallWidth = null;
	protected $_smallHeight = null;
	protected $_smallSource = null;
	protected $_smallUrl = null;
	protected $_smallMedia = null;
	
	protected $_mediumWidth = null;
	protected $_mediumHeight = null;
	protected $_mediumSource = null;
	protected $_mediumUrl = null;
	protected $_mediumMedia = null;
	
	protected $_largeWidth = null;
	protected $_largeHeight = null;
	protected $_largeSource = null;
	protected $_largeUrl = null;
	protected $_largeMedia = null;
	
	protected $_originalWidth = null;
	protected $_originalHeight = null;
	protected $_originalSource = null;
	protected $_originalUrl = null;
	protected $_originalMedia = null;
	
	
	
	// length 75px
	public function getSquareWidth()
	{
		return $this->_squareWidth;
	}
	
	
	public function getSquareHeight()
	{
		return $this->_squareHeight;
	}
	
	
	public function getSquareSource()
	{
		return $this->_squareSource;
	}
	
	
	public function getSquareUrl()
	{
		return $this->_squareUrl;
	}
	
	
	public function getSquareMedia()
	{
		return $this->_squareMedia;
	}
	
	
	
	
	
	// length 100px
	public function getThumbnailWidth()
	{
		return $this->_thumbnailWidth;
	}
	
	
	public function getThumbnailHeight()
	{
		return $this->_thumbnailHeight;
	}
	
	
	public function getThumbnailSource()
	{
		return $this->_thumbnailSource;
	}
	
	
	public function getThumbnailUrl()
	{
		return $this->_thumbnailUrl;
	}
	
	
	public function getThumbnailMedia()
	{
		return $this->_thumbnailMedia;
	}
	
	
	
	
	// length 240px
	public function getSmallWidth()
	{
		return $this->_smallWidth;
	}
	
	
	public function getSmallHeight()
	{
		return $this->_smallHeight;
	}
	
	
	public function getSmallSource()
	{
		return $this->_smallSource;
	}
	
	
	public function getSmallUrl()
	{
		return $this->_smallUrl;
	}
	
	
	public function getSmallMedia()
	{
		return $this->_smallMedia;
	}
	
	
	
	
	// length 500px
	public function getMediumWidth()
	{
		return $this->_mediumWidth;
	}
	
	
	public function getMediumHeight()
	{
		return $this->_mediumHeight;
	}
	
	
	public function getMediumSource()
	{
		return $this->_mediumSource;
	}
	
	
	public function getMediumUrl()
	{
		return $this->_mediumUrl;
	}
	
	
	public function getMediumMedia()
	{
		return $this->_mediumMedia;
	}
	
	
	
	
	// length 1024px
	public function getLargeWidth()
	{
		return $this->_largeWidth;
	}
	
	
	public function getLargeHeight()
	{
		return $this->_largeHeight;
	}
	
	
	public function getLargeSource()
	{
		return $this->_largeSource;
	}
	
	
	public function getLargeUrl()
	{
		return $this->_largeUrl;
	}
	
	
	public function getLargeMedia()
	{
		return $this->_largeMedia;
	}
	
	
	
	
	
	public function getOriginalWidth()
	{
		return $this->_originalWidth;
	}
	
	
	public function getOriginalHeight()
	{
		return $this->_originalHeight;
	}
	
	
	public function getOriginalSource()
	{
		return $this->_originalSource;
	}
	
	
	public function getOriginalUrl()
	{
		return $this->_originalUrl;
	}
	
	
	public function getOriginalMedia()
	{
		return $this->_originalMedia;
	}
	
	
	
	
	
	public function startByPhotoId(\Sirprize\Flickr\Id $photoId)
	{
		if($this->_started)
		{
			return $this;
		}
		
		$this->_started = true;
		
		require_once 'Zend/Uri.php';
		$uri = \Zend_Uri::factory('http://api.flickr.com/services/rest/');
		
		$args = array(
			'api_key' => $this->_getService()->getApiKey(),
			'format' => 'php_serial',
			'method' => 'flickr.photos.getSizes',
			'photo_id' => (string) $photoId
		);
		
		try {
			/*
			$this->_responseHandler = $this->_getService()->getResponseHandlerInstance();
			
			$this->_getRestClient()
				->setCacheIdFromParts(array(__METHOD__, $photoId))
				->setResponseHandler($this->_responseHandler)
				->setUri($uri)
				->get($args)
			;
			*/
			
			$this->_getRestClient()
				->getHttpClient()
				->resetParameters()
				->setUri($uri)
				->setParameterGet($args)
			;
			
			$cacheId = $this->_getService()->makeCacheIdFromParts(array(__METHOD__, $photoId));
			$this->_responseHandler = $this->_getService()->getResponseHandlerInstance();
			$this->_getRestClient()->get($this->_responseHandler, 2, array(), $cacheId);
			
			if($this->_responseHandler->isError())
			{
				// service error
				$this->_onStartError($this->_getOnStartErrorMessage($this->_responseHandler->getMessage()));
				return $this;
			}
			
			$data = $this->_responseHandler->getPhp();
			
			foreach($data['sizes']['size'] as $item)
			{
				switch($item['label'])
				{
					case 'Square': {
						$this->_squareWidth = $item['width'];
						$this->_squareHeight = $item['height'];
						$this->_squareSource = $item['source'];
						$this->_squareUrl = $item['url'];
						$this->_squareMedia = $item['media'];
						break;
					}
					case 'Thumbnail': {
						$this->_thumbnailWidth = $item['width'];
						$this->_thumbnailHeight = $item['height'];
						$this->_thumbnailSource = $item['source'];
						$this->_thumbnailUrl = $item['url'];
						$this->_thumbnailMedia = $item['media'];
						break;
					}
					case 'Small': {
						$this->_smallWidth = $item['width'];
						$this->_smallHeight = $item['height'];
						$this->_smallSource = $item['source'];
						$this->_smallUrl = $item['url'];
						$this->_smallMedia = $item['media'];
						break;
					}
					case 'Medium': {
						$this->_mediumWidth = $item['width'];
						$this->_mediumHeight = $item['height'];
						$this->_mediumSource = $item['source'];
						$this->_mediumUrl = $item['url'];
						$this->_mediumMedia = $item['media'];
						break;
					}
					case 'Large': { // only with free account
						$this->_largeWidth = $item['width'];
						$this->_largeHeight = $item['height'];
						$this->_largeSource = $item['source'];
						$this->_largeUrl = $item['url'];
						$this->_largeMedia = $item['media'];
						break;
					}
					case 'Original': { // only with pro account
						$this->_originalWidth = $item['width'];
						$this->_originalHeight = $item['height'];
						$this->_originalSource = $item['source'];
						$this->_originalUrl = $item['url'];
						$this->_originalMedia = $item['media'];
						break;
					}
				}
			}
			
			$this->_onStartSuccess($this->_getOnStartSuccessMessage($photoId));
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
	
	
	
	protected function _getOnStartSuccessMessage($photoId)
	{
		return "started sizes for photo '".$photoId."'";
	}
	
	
	
	protected function _getOnStartErrorMessage($errorMessage)
	{
		return "photo sizes could not be started: '".$errorMessage."'";
	}
}