<?php

class DateTimePropertyModel extends \Gietos\Model\AbstractModel
{
    protected $propertyDateTime;

    protected function setPropertyDateTime(string $dateTime)
    {
        $this->propertyDateTime = new \DateTime($dateTime);
    }

    protected function getPropertyDateTime(): \DateTime
    {
        return $this->propertyDateTime;
    }
}