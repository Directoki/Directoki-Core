<?php

namespace DirectokiBundle\Form\Type;

use DirectokiBundle\Entity\RecordHasFieldTextValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class RecordHasFieldTextValueType extends BaseRecordHasFieldValueType {

    /** @var RecordHasFieldTextValue  */
    protected $recordHasTextFieldValue;

    function __construct(RecordHasFieldTextValue $recordHasTextFieldValue ) {
        $this->recordHasTextFieldValue = $recordHasTextFieldValue;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('value', TextareaType::class, array(
            'required' => false,
            'label'=>'Value',
            'data' => $this->recordHasTextFieldValue->getValue()
        ));


        parent::buildForm($builder, $options);
    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
        );
    }

}
