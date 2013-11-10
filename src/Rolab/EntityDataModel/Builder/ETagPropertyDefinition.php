<?php

/*
 * This file is part of the Rolab Entity Data Model library.
 *
 * (c) Roland Schermer <roland0507@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rolab\EntityDataModel\Builder;

use Rolab\EntityDataModel\Builder\PrimitivePropertyDefition;

class ETagPropertyDefinition extends PrimitivePropertyDefition
{
	public function __construct($name, $typeName)
	{
		parent::__construct($name, $typeName, false);
	}
}