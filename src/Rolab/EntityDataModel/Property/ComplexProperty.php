<?php

/*
 * This file is part of the Rolab Entity Data Model library.
 *
 * (c) Roland Schermer <roland0507@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rolab\EntityDataModel\Property;

use Rolab\EntityDataModel\Property\ResourceProperty;
use Rolab\EntityDataModel\Type\ComplexType;

class ComplexProperty extends ResourceProperty
{
	public function __construct($name, ComplexType $type)
	{
		parent::__construct($name, $type);
	}
}
