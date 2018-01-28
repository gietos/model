<?php

namespace Gietos\Model;

use Doctrine\Common\Inflector\Inflector;

trait PackTrait
{
    /**
     * @param object $object
     * @param string $attribute
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function packAttribute($object, $attribute)
    {
        $classReflection = new \ReflectionClass($object);
        $propertyNameClassified = Inflector::classify($attribute); // SelectionCriteria

        if ($classReflection->hasMethod('pack' . $propertyNameClassified)) { // $object->packSelectionCriteria()
            $propertyValue = call_user_func([$object, 'pack' . $propertyNameClassified]);
        } elseif ($classReflection->hasMethod('get' . $propertyNameClassified)) { // try to call $object->getSelectionCriteria()
            $propertyValue = call_user_func([$object, 'get' . $propertyNameClassified]);
        } elseif (property_exists($this, $attribute)) { // try to get value of $object->selectionCriteria
            $propertyReflection = $classReflection->getProperty($attribute);
            if ($propertyReflection->isPublic()) {
                $propertyValue = $propertyReflection->getValue($object);
            } else {
                throw new \LogicException(
                    sprintf(
                        'Could not get value of non-public property %s of class %s. Either set visibility to public or define getter method',
                        $attribute,
                        get_class($object)
                    )
                );
            }
        } else {
            throw new \LogicException(
                sprintf(
                    'Could not get value of attribute %s of class %s. Either getter or public property should be defined',
                    $attribute,
                    get_class($object)
                )
            );
        }

        return $this->packValue($propertyValue);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function packValue($value)
    {
        if (count(debug_backtrace()) > Packable::RECURSION_LIMIT) {
            throw new \RuntimeException(sprintf('Max recursion count of %s reached', Packable::RECURSION_LIMIT));
        }

        if (is_array($value)) {
            $finalValue = [];
            foreach ($value as $subValue) {
                $finalValue[] = $this->packValue($subValue);
            }
            return $finalValue;
        } elseif ($value instanceof Packable) {
            return $value->pack();
        }

        return $value;
    }
}
