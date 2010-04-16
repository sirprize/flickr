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


namespace Sirprize\Flickr\Core\Entity;


/**
 * Class to observe an entity and print state changes to stout
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Observer
{
	
	
	public function onStartSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onCreateSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onUpdateSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onDeleteSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	
	
	
	
	public function onStartError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onCreateError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onUpdateError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onDeleteError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		print $message."\n";
	}
	
}