<?php

namespace Gietos\Model;

use Doctrine\Common\Inflector\Inflector;

abstract class AbstractModel implements Packable, Configurable
{
    use PackTrait;

    /**
     * @return array
     */
    public function attributes()
    {
        $array = [];
        foreach ($this as $key => $value) {
            $array[] = $key;
        }

        return $array;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function pack()
    {
        $array = [];
        foreach ($this->attributes() as $attribute) { // example: selectionCriteria
            $value = $this->packAttribute($this, $attribute);
            if (null === $value) {
                continue;
            }
            $array[$attribute] = $value;
        }

        return $array;
    }

    /**
     * @param array|string $config
     *
     * @throws \ReflectionException
     */
    public function configure($config = [])
    {
        foreach ($config as $key => $value) {
            $propertyName = Inflector::camelize($key);
            $this->setAttribute($propertyName, $value);
        }
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function createObjects(\ReflectionClass $class, ...$values)
    {
        foreach ($values as $i => $value) {
            $values[$i] = $this->createObject($class, $value);
        }

        return $values;
    }

    /**
     * @param \ReflectionClass $class
     * @param $value
     * @return object
     * @throws \LogicException
     */
    private function createObject(\ReflectionClass $class, $value)
    {
        if (in_array($class->getName(), [\DateTime::class, \DateTimeZone::class])) {
            return $class->newInstance($value);
        }

        if (!$class->implementsInterface(Configurable::class)) {
            throw new \LogicException(
                sprintf(
                    'Failed to configure object of class %s, class MUST implement ConfigurableInterface',
                    $class->getName()
                )
            );
        }
        $object = $class->newInstanceWithoutConstructor();
        $object->configure($value);

        return $object;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \ReflectionException
     */
    public function setAttribute($name, $value)
    {
        if (method_exists($this, 'unpack' . ucfirst($name))) {
            $methodReflection = new \ReflectionMethod($this, 'unpack' . ucfirst($name));
        } elseif(method_exists($this, 'set' . ucfirst($name))) {
            $methodReflection = new \ReflectionMethod($this, 'set' . ucfirst($name));
        }

        if (isset($methodReflection)) {
            $params = $methodReflection->getParameters();
            if (count($params) !== 1) {
                throw new \InvalidArgumentException(sprintf(
                    'Only methods with exactly 1 argument is supported. Unsupported method: %s',
                    $methodReflection->getName()
                ));
            }
            $param = $params[0];
            if ($class = $param->getClass()) {
                if (is_array($value) && !$this->isAssoc($value)) {
                    $methodReflection->invokeArgs($this, $this->createObjects($class, ...$value));

                    return;
                } else {
                    $value = $this->createObject($class, $value);
                }
            }
            $methodReflection->invokeArgs($this, [$value]);

            return;
        }

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    public function flatten()
    {
        $result = [];
        $array = (array) $this;
        array_walk_recursive($array, function($x) use (&$result) {
            if ($x !== null) {
                $result[] = $x;
            }
        });
        return $result;
    }
}
