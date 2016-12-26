<?php

use Gietos\Model\SkipExport;

class SkipExportModel extends \Gietos\Model\AbstractModel
{
    protected $propertyToExport;
    protected $propertyToSkip;

    public function getPropertyToSkip(): string
    {
        return $this->propertyToSkip;
    }

    public function exportPropertyToSkip(): array
    {
        return ['propertyToSkip', new SkipExport];
    }
}
