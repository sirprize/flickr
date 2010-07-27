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


namespace Sirprize\Flickr\Rest\ResponseHandler;


#require_once 'Sirprize/Rest/ResponseHandler/Php.php';


class Php extends \Sirprize\Rest\ResponseHandler\Php
{
	
	public function load(\Zend_Http_Response $httpResponse)
    {
		parent::load($httpResponse);

		if($this->_php['stat'] == 'fail')
		{
			$this->_code = $this->_php['code'];
			$this->_message = $this->_php['message'];
		}
		
		return $this;
    }
	
}