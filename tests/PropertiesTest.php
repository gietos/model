<?php

use PHPUnit\Framework\TestCase;

class PropertiesTest extends TestCase
{
    public function testIntProperty()
    {
        $model = new IntPropertyModel(['propertyInt' => 1]);

        $this->assertEquals(['property_int' => 1], $model->export());
    }

    public function testDateTimeProperty()
    {
        $model = new DateTimePropertyModel(['propertyDateTime' => '2016-01-01 00:00:00']);

        $this->assertEquals(['property_date_time' => '2016-01-01 00:00:00'], $model->export());
    }
}
