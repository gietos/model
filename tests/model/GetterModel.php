<?php

class GetterModel extends \Gietos\Model\AbstractModel
{
    protected $propertyToExport;

    public function getPropertyToExport(): string
    {
        return 'Exported-' . $this->propertyToExport;
    }
}