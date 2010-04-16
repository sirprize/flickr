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


namespace Sirprize\Flickr\Core\Entity\Observer;


require_once 'Sirprize/Flickr/Core/Entity/Observer.php';


/**
 * Class to observe an entity and log state changes
 *
 * @category  Sirprize
 * @package   Flickr
 */
class Log extends \Sirprize\Flickr\Core\Entity\Observer
{
	
	
	protected $_log = null;
	
	
	public function setLog(\Zend_Log $log)
	{
		$this->_log = $log;
		return $this;
	}
	
	
	protected function _getLog()
	{
		if($this->_log === null)
		{
			require_once 'Sirprize/Flickr/Exception.php';
			throw new \Sirprize\Flickr\Exception('call setLog() before '.__METHOD__);
		}
		
		return $this->_log;
	}
	
	
	public function onCreateSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->info($message);
	}
	
	
	public function onUpdateSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->info($message);
	}
	
	
	public function onDeleteSuccess(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->info($message);
	}
	
	
	
	
	
	public function onCreateError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->err($message);
	}
	
	
	public function onUpdateError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->err($message);
	}
	
	
	public function onDeleteError(\Sirprize\Flickr\Core\Entity $entity, $message = null)
	{
		$this->_getLog()->err($message);
	}
	
}