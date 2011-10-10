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


#require_once 'Sirprize/Flickr/Core/Entity.php';


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
	protected $_description = null;
	protected $_photoSets = null;
	
	
	public function getId()
	{
		return $this->_id;
	}
	
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	
	public function getDescription()
	{
		return $this->_description;
	}
	
	
	public function getPhotoSets()
	{
		if($this->_photoSets === null)
		{
			$this->_photoSets = $this->_getService()->getPhotoSetsInstance();
		}
		
		return $this->_photoSets;
	}
	
	
	/**
	 * Load data returned from an api request
	 *
	 * @throws \Sirprize\Flickr\Exception
	 * @return \Sirprize\Flickr\Collection
	 */
	public function load(array $data, $force = false)
	{
		if($this->_loaded && !$force)
		{
			#require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('entity has already been loaded');
		}
		
		$this->_loaded = true;
		
		#require_once 'Sirprize/Flickr/Id.php';
		$id = new \Sirprize\Flickr\Id($data['id']);
		
		$this->_id = $id;
		$this->_title = $data['title'];
		$this->_description = $data['description'];
		
		foreach($data['set'] as $item)
		{
			$photoSet = $this->getPhotoSets()->getPhotoSetInstance();
			$photoSet->load($item);
			$this->getPhotoSets()->attach($photoSet);
		}
		
		return $this;
	}
	
}