<?php

namespace DirectokiBundle\Form\Type;

use DirectokiBundle\Entity\DataHasStringField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class DirectoryNewType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('titleSingular', 'text', array(
            'required' => true,
            'label'=>'Title (Singular)',
        ));

        $builder->add('titlePlural', 'text', array(
            'required' => true,
            'label'=>'Title (Plural)',
        ));


        $builder->add('publicId', 'text', array(
            'required' => true,
            'label'=>'Public Id (Singular)',
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
