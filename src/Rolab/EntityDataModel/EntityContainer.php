<?php

declare(strict_types=1);

namespace Rolab\EntityDataModel;

use PhpCollection\Map;
use PhpCollection\MapInterface;
use PhpOption\None;
use PhpOption\Option;

use Rolab\EntityDataModel\Exception\InvalidArgumentException;

/**
 * Defines a logical grouping of entities.
 *
 * An entity container is used in the OData protocol specify the service's
 * entity sets and singletons. An entity container may have a parent container,
 * in which case entity sets in the child container may reference the entity
 * sets in the parent container.
 *
 * @author Roland Schermer <roland0507@gmail.com>
 */
class EntityContainer implements NamedModelConstruct
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var EntityDataModel
     */
    private $entityDataModel;

    /**
     * @var Option
     */
    private $parentContainer;

    /**
     * @var MapInterface
     */
    private $entitySets;
    
    /**
     * Creates a new entity container.
     * 
     * Creates a new entity container. A parent container may be specified, in which
     * case entity sets and association sets in the container may reference any of the
     * entity sets and association sets in the parent container.
     * 
     * @param string               $name            The name of the entity container (must contain 
     *                                              only alphanumeric characters and underscores).
     * @param EntityDataModel      $entityDataModel The entity data model this entity container is
     *                                              defined on.
     * @param null|EntityContainer $parentContainer An optional parent container for the entity 
     *                                              container
     * 
     * @throws InvalidArgumentException Thrown if the container's name contains illegal characters.
     */
    public function __construct(
        string $name,
        EntityDataModel $entityDataModel,
        EntityContainer $parentContainer = null
    ) {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is an illegal name for an entity container. The name for an entity container may only ' .
                'contain alphanumeric characters and underscores.',
                $name
            ));
        }

        $this->name = $name;
        $this->entityDataModel = $entityDataModel;
        $this->parentContainer = Option::fromValue($parentContainer);
        $this->entitySets = new Map();

        if (!$this->parentContainer->map(function ($parentContainer) use ($entityDataModel) {
                return $entityDataModel->getReferencedModels()->contains($parentContainer->getEntityDataModel());
            })->getOrElse(false)
        ) {
            $entityDataModel->addReferencedModel($this->parentContainer->get()->getEntityDataModel());
        }
    }

    /**
     * Returns the entity data model the entity container is defined on.
     *
     * @return EntityDataModel The entity data model the entity container is defined on..
     */
    public function getEntityDataModel() : EntityDataModel
    {
        return $this->entityDataModel;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace() : string
    {
        return $this->entityDataModel->getNamespace();
    }

    /**
     * {@inheritDoc}
     */
    public function getFullName() : string
    {
        $namespace = $this->getNamespace();

        return $namespace ? $namespace .'.'. $this->getName() : $this->getName();
    }
    
    /**
     * Returns the entity container's parent container if one was set.
     * 
     * @return Option The entity container's parent container wrapped in Some or None if no
     *                parent container was specified.
     */
    public function getParentContainer() : Option
    {
        return $this->parentContainer;
    }
    
    /**
     * Adds an entity set to the entity container.
     * 
     * Adds an entity set to the entity container. Entity sets within one entity
     * container must have unique names. However, an entity set may have the same
     * name as another entity set in the parent container, in which case the entity
     * set in the parent container is overridden.
     * 
     * @param EntitySet $entitySet The entity set to be added to the entity container
     * 
     * @throws InvalidArgumentException Thrown if an entity set is added with a name that
     *                                  is already in use by another entity set in the same
     *                                  entity container.
     */
    public function addEntitySet(EntitySet $entitySet)
    {
        if ($this->entitySets->containsKey($entitySet->getName())) {
            throw new InvalidArgumentException(sprintf(
                'Tried to add entity set with name "%s" to entity container "%s", but this entity container already ' .
                'contains an element with that name. Entity container elements within the same entity container must' .
                'have unique names',
                $entitySet->getName(),
                $this->getFullName()
            ));
        }

        $this->entitySets->set($entitySet->getName(), $entitySet);
        $entitySet->setEntityContainer($this);
    }
    
    /**
     * Returns all entity sets in the current entity container.
     * 
     * Returns all entity sets in the current entity container.
     * 
     * @return MapInterface A map containing all entity sets in the entity container keyed
     *                      by name.
     */
    public function getEntitySets() : MapInterface
    {
        return $this->entitySets;
    }
    
    /**
     * Searches the entity container for an entity set with a specific name.
     * 
     * Searches the entity container for an entity set with a specific name and if an
     * entity set with that name exists in either the container itself, or the parent
     * container, an entity set will be returned. If the both the child container and
     * the parent container contain an entity set with the same name, only the entity
     * set in the child container will be returned.
     *
     * @param string $name The name of the entity set.
     * 
     * @return Option An entity set with the name searched for wrapped in Some or None if no
     *                such entity set exists in the container.
     */
    public function getEntitySetByName(string $name) : Option
    {
        $currentContainer = $this;

        $this->entitySets->get($name);

        if ($this->entitySets->containsKey($name)) {
            return $this->entitySets->get($name);
        }

        while ($currentContainer->getParentContainer()->isDefined()) {
            $parentContainer = $currentContainer->getParentContainer()->get();

            if ($parentContainer->getEntitySets()->containsKey($name)) {
                return $parentContainer->getEntitySets()->get($name);
            }

            $currentContainer = $parentContainer;
        }

        return None::create();
    }
}
