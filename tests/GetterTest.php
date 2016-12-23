<?php

use PHPUnit\Framework\TestCase;

class GetterTest extends TestCase
{
    public function testGetter()
    {
        $model = new GetterModel(['propertyToExport' => 'String']);

        $this->assertEquals(['property_to_export' => 'Exported-String'], $model->export());
    }
}
