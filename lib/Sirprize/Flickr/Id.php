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

namespace Sirprize\Flickr;

class Id
{
	
	protected $_id = null;
	
	
	public function __construct($id)
	{
		if(!preg_match('/^[a-zA-Z0-9@_-]+$/', $id))
		{
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception("invalid id format '$id'");
		}
		
		$this->_id = $id;
	}
	
	
	public function __toString()
	{
		return trim($this->_id);
	}
	
	
	public function get()
	{
		return trim($this->_id);
	}
}