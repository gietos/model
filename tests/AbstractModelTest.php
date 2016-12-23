<?php

use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{
    public function testConstruct()
    {
        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder(\Gietos\Model\AbstractModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        // set expectations for constructor calls
        $mock->expects($this->once())
            ->method('configure')
            ->with(
                $this->equalTo(['property' => 'value'])
            );

        // now call the constructor
        $reflectedClass = new ReflectionClass(\Gietos\Model\AbstractModel::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, ['property' => 'value']);
    }
}