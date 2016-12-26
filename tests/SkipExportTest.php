<?php

use PHPUnit\Framework\TestCase;

class SkipExportTest extends TestCase
{
    public function testSkipExport()
    {
        $model = new SkipExportModel([
            'propertyToExport' => 'String1',
            'propertyToSkip' => 'String2',
        ]);

        $this->assertEquals(['property_to_export' => 'String1'], $model->export());
    }

    public function testPropertyCanBeRead()
    {
        $model = new SkipExportModel([
            'propertyToExport' => 'String1',
            'propertyToSkip' => 'String2',
        ]);

        $this->assertEquals('String2', $model->getPropertyToSkip());
    }
}
