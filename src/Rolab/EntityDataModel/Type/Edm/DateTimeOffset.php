<?php

declare(strict_types=1);

namespace Rolab\EntityDataModel\Type\Edm;

class DateTimeOffset extends EdmPrimitiveType
{
    public function getName() : string
    {
        return 'DateTimeOffset';
    }
}
