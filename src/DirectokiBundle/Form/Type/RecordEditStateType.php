<?php

namespace DirectokiBundle\Form\Type;

use DirectokiBundle\Entity\DataHasStringField;
use DirectokiBundle\Entity\RecordHasState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class RecordEditStateType extends AbstractType {


    /** @var RecordHasState */
    protected $recordHasState;

    function __construct(RecordHasState $recordHasState ) {
        $this->recordHasState = $recordHasState;
    }


    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('state', ChoiceType::class, array(
            'required' => true,
            'label'=>'State',
            'choices_as_values' => true,
            'choices'=>array(
                'Draft' => RecordHasState::STATE_DRAFT,
                'Published' => RecordHasState::STATE_PUBLISHED,
                'Deleted' => RecordHasState::STATE_DELETED,
            ),
        ));

        $builder->add('approve',  CheckboxType::class, array(
            'required' => false,
            'label'=>'Approve instantly?',
            'data' =>true,
        ));

        $builder->add('createdComment', TextareaType::class, array(
            'required' => false,
            'label' => 'Comment on change'
        ));


    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
        );
    }

}




