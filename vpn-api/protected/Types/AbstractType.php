<?php

/**
 * Abstract base entity DTO

 * Created by PhpStorm.
 * User: traffic2005
 * Date: 17.10.17
 * Time: 14:53
 */

namespace App\Types;

abstract class AbstractType
{

    /**
     * Constructor with property hydration
     * 
     * @param array $entityData Entity data
     */
    public function __construct(array $entityData = [])
    {
        foreach ($entityData as $field => $value) {
            if (property_exists($this, $field)) {
                $method = 'set' . ucfirst($field);
                $this->$method($value);
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $array           = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }
        return $array;
    }
}
