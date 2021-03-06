<?php

namespace DirectokiBundle\Entity;



use DirectokiBundle\FieldType\FieldTypeString;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 * @ORM\Entity(repositoryClass="DirectokiBundle\Repository\RecordHasFieldStringValueRepository")
 * @ORM\Table(name="directoki_record_has_field_string_value")
 * @ORM\HasLifecycleCallbacks
 */
class RecordHasFieldStringValue extends BaseRecordHasFieldValue
{



    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    protected $value;



    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = FieldTypeString::filterValue($value);
    }


}

