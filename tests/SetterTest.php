<?php

use PHPUnit\Framework\TestCase;

class SetterTest extends TestCase
{
    public function testSetter()
    {
        $model = new SetterModel(['propertyToImport' => 'String']);

        $this->assertEquals(['property_to_import' => 'Imported-String'], $model->export());
    }
}
