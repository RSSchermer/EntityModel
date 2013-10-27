<?php

/*
 * This file is part of the Rolab Entity Data Model library.
 *
 * (c) Roland Schermer <roland0507@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rolab\ODataProducer\Model\Type;

abstract class ResourceType
{
	abstract public function getName();
	
	abstract public function getNamespace();
	
	abstract public function getFullName();
}