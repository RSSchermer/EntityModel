<?php

/*
 * This file is part of the Rolab Entity Data Model library.
 *
 * (c) Roland Schermer <roland0507@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RSSchermer\Tests\EntityModel\ClassMetadata;

use RSSchermer\EntityModel\ClassMetadata\ComplexTypeMetadata;

/**
 * @covers StructuralTypeMetadata
 */
class ComplexTypeMetadataTest
{
    public function testSerializeUnserialize()
    {
        $metadata = new ComplexTypeMetadata('EntityDataModel\Tests\Fixtures\Adress');
        $metadata->typeName = 'Adress';

        $this->assertEquals($metadata, unserialize(serialize($metadata)));
    }
}
