<?php

class SetterModel extends \Gietos\Model\AbstractModel
{
    protected $propertyToImport;

    public function setPropertyToImport(string $property)
    {
        $this->propertyToImport = 'Imported-' . $property;
    }
}