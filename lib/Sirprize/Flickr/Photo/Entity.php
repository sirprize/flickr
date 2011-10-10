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




/**
 * Represent and modify an entity
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Entity extends \Sirprize\Flickr\Core\Entity
{
	
	protected $_id = null;
	protected $_title = null;
	protected $_sizes = null;
	protected $_info = null;
	
	
	public function getId()
	{
		return $this->_id;
	}
	
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	
	/**
	 * Load data returned from an api request
	 *
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Photo
	 */
	public function load(array $data, $force = false)
	{
		if($this->_loaded && !$force)
		{
			throw new \Sirprize\Flickr\Exception('entity has already been loaded');
		}
		
		$this->_loaded = true;
		
		$id = new \Sirprize\Flickr\Id($data['id']);
		
		$this->_id = $id;
		$this->_title = $data['title'];
		return $this;
	}
	


	protected function _checkIsLoaded()
	{
		if(!$this->_loaded)
		{
			throw new \Sirprize\Flickr\Exception('call load() before '.__METHOD__);
		}
	}
	
	
	
	public function getSizes()
	{
		if($this->_sizes !== null)
		{
			return $this->_sizes;
		}
		
		$this->_checkIsLoaded();
		
		$this->_sizes = new \Sirprize\Flickr\Photo\Sizes();
		$this->_sizes
			->setRestClient($this->_getRestClient())
			->setService($this->_getService())
			->startByPhotoId($this->getId())
		;
		
		return $this->_sizes;
	}
	
	
	
	public function getInfo()
	{
		if($this->_info !== null)
		{
			return $this->_info;
		}
		
		$this->_checkIsLoaded();
		
		$this->_info = new \Sirprize\Flickr\Photo\Info();
		$this->_info
			->setRestClient($this->_getRestClient())
			->setService($this->_getService())
			->startByPhotoId($this->getId())
		;
		
		return $this->_info;
	}
	
}