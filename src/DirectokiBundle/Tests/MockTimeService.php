<?php

namespace DirectokiBundle\Tests;

use DirectokiBundle\Entity\Field;
use DirectokiBundle\FieldType\FieldTypeBoolean;
use DirectokiBundle\FieldType\FieldTypeEmail;
use DirectokiBundle\FieldType\FieldTypeLatLng;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeMultiSelect;
use DirectokiBundle\FieldType\FieldTypeStringWithLocale;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\FieldType\FieldTypeURL;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class MockTimeService
{

    protected $now;

    /**
     * MockTimeService constructor.
     * @param $now
     */
    public function __construct(\DateTime $now)
    {
        $this->now = $now;
    }

    public function getDateTimeNowUTC() {
        $dt = clone $this->now;
        $dt->setTimezone(new \DateTimeZone('utc'));
        return $dt;
    }

}