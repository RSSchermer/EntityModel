<?php

declare(strict_types=1);

namespace RSSchermer\EntityModel\Type;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

use RSSchermer\EntityModel\Exception\InvalidArgumentException;

/**
 * Describes a property on a structured type.
 *
 * @author Roland Schermer <roland0507@gmail.com>
 */
abstract class AbstractResourcePropertyDescription
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \ReflectionProperty
     */
    private $reflection;

    /**
     * @var Option
     */
    private $structuredType;

    /**
     * @var ResourceTypeInterface
     */
    private $propertyValueType;

    /**
     * @var boolean
     */
    private $isCollection;

    /**
     * @var boolean
     */
    private $nullable;

    /**
     * Creates a new resource property description.
     *
     * @param string              $name              The name of the structural property description. (may
     *                                               only consist of alphanumeric characters and the
     *                                               underscore).
     * @param \ReflectionProperty $reflection        A reflection object for the property being described.
     * @param ResourceTypeInterface        $propertyValueType The type of the property value.
     * @param boolean             $isCollection      Whether or not the property value is a collection.
     * @param boolean             $nullable          Whether or not the property is nullable.
     *
     * @throws InvalidArgumentException Thrown if the name contains illegal characters.
     */
    public function __construct(
        string $name,
        \ReflectionProperty $reflection,
        ResourceTypeInterface $propertyValueType,
        bool $isCollection = false,
        bool $nullable = true
    ) {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is an illegal name for a property description. The name for a property descriptions may only ' .
                'contain alphanumeric characters and underscores.',
                $name
            ));
        }

        $this->name = $name;
        $this->reflection = $reflection;
        $this->propertyValueType = $propertyValueType;
        $this->isCollection = $isCollection;
        $this->nullable = $nullable;
        $this->structuredType = None::create();
    }

    /**
     * Sets the structured type this resource property description belongs to.
     *
     * @param ComplexType $structuredType The structured type this resource property description
     *                                 belongs to.
     */
    public function setStructuredType(ComplexType $structuredType)
    {
        $this->structuredType = new Some($structuredType);
    }

    /**
     * Returns the structured type this resource property description belongs to.
     *
     * @return Option The structured type this resource property description belongs to wrapped
     *                in Some, or None of no structured type has been set.
     */
    public function getStructuredType() : Option
    {
        return $this->structuredType;
    }

    /**
     * Returns the name of the resource property description.
     *
     * @return string The name of the resource property description.
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Returns the reflection for the property that is described by the resource
     * property description.
     *
     * @return \ReflectionProperty The reflection for the property that is described by
     *                             the resource property description.
     */
    public function getReflection() : \ReflectionProperty
    {
        return $this->reflection;
    }

    /**
     * Returns this property's value type.
     *
     * @return ResourceTypeInterface This property's value type.
     */
    public function getPropertyValueType() : ResourceTypeInterface
    {
        return $this->propertyValueType;
    }

    /**
     * Returns true if this property hold a collection of values, false if it holds
     * a singular value.
     *
     * @return bool Whether or not this property holds a collection of values.
     */
    public function isCollection() : bool
    {
        return $this->isCollection;
    }

    /**
     * Returns true if this property's value can be set to null, false if it cannot.
     *
     * @return bool Whether or not this property's value can be set to null.
     */
    public function isNullable() : bool
    {
        return $this->nullable;
    }
}
