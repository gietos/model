<?php

namespace Gietos\Model;

use DateTime;
use Doctrine\Common\Inflector\Inflector;
use ReflectionClass;

abstract class AbstractModel implements Configurable, Exportable
{
    public const DATE_EXPORT_FORMAT = 'Y-m-d H:i:s';

    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    public function configure(array $config)
    {
        foreach ($config as $key => $value) {
            $propertyName = Inflector::camelize($key);
            $customSetter = 'set' . ucfirst($propertyName);
            if (method_exists($this, $customSetter)) {
                call_user_func([$this, $customSetter], $value);
                continue;
            }

            if (property_exists($this, $propertyName)) {
                $this->{$propertyName} = $value;
            }
        }
    }

    public function export(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $reflectionProperties = $reflectionClass->getProperties();

        $result = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $customGetter = 'get' . ucfirst($reflectionProperty->getName());
            if (method_exists($this, $customGetter)) {
                $propertyValue = call_user_func([$this, $customGetter]);
            } else {
                $propertyValue = $this->{$reflectionProperty->getName()};
            }

            $result[$this->exportName($reflectionProperty->getName())] = $this->exportValue($propertyValue);
        }

        return $result;
    }

    protected function exportValue($propertyValue)
    {
        if (is_array($propertyValue)) {
            $buffer = [];
            foreach ($propertyValue as $item) {
                $buffer[] = $this->exportValue($item);
            }
            return $buffer;
        }

        if ($propertyValue instanceof Exportable) {
            return $propertyValue->export();
        }

        if ($propertyValue instanceof DateTime) {
            return $propertyValue->format(static::DATE_EXPORT_FORMAT);
        }

        return $propertyValue;
    }

    protected function exportName(string $name): string
    {
        return Inflector::tableize($name);
    }
}