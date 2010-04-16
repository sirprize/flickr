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


namespace Sirprize\Flickr\Core\Collection;


/**
 * Class to observe collections and print state changes to stout
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Observer
{
	
	public function onStartSuccess(\Sirprize\Flickr\Core\Collection $collection, $message = null)
	{
		print $message."\n";
	}
	
	
	public function onStartError(\Sirprize\Flickr\Core\Collection $collection, $message = null)
	{
		print $message."\n";
	}
	
}