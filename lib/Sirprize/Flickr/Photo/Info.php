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
class Info extends \Sirprize\Flickr\Core\Entity
{
	
	protected $_description = null;
	
	
	public function getDescription()
	{
		return $this->_description;
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
			'method' => 'flickr.photos.getInfo',
			'photo_id' => (string) $photoId
		);
		
		try {
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
			$this->_description = $data['photo']['description']['_content'];
			
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
		return "started info for photo '".$photoId."'";
	}
	
	
	
	protected function _getOnStartErrorMessage($errorMessage)
	{
		return "photo info could not be started: '".$errorMessage."'";
	}
}