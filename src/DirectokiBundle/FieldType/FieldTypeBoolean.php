<?php

namespace DirectokiBundle\FieldType;


use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Record;
use DirectokiBundle\Entity\RecordHasBooleanFieldValue;
use DirectokiBundle\Entity\Field;
use DirectokiBundle\Entity\RecordHasFieldBooleanValue;
use DirectokiBundle\Entity\User;
use DirectokiBundle\Form\Type\RecordHasBooleanFieldValueType;
use DirectokiBundle\Form\Type\RecordHasFieldBooleanValueType;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 *
 */
class FieldTypeBoolean extends  BaseFieldType {

    const FIELD_TYPE_INTERNAL = 'boolean';
    const FIELD_TYPE_API1 = 'boolean';

    public function getLatestFieldValues(Field $field, Record $record) {
        return array($this->getLatestFieldValue($field, $record));
    }
    protected  function getLatestFieldValue(Field $field, Record $record) {

        $repo = $this->container->get('doctrine')->getManager()->getRepository('DirectokiBundle:RecordHasFieldBooleanValue');

        $r = $repo->findLatestFieldValue($field, $record);

        if (!$r) {
            $r = new RecordHasFieldBooleanValue();
        }

        return $r;

    }

    public function getFieldValuesToModerate(Field $field, Record $record) {

        $repo = $this->container->get('doctrine')->getManager()->getRepository('DirectokiBundle:RecordHasFieldBooleanValue');

        return $repo->getFieldValuesToModerate($field, $record);
    }

    public function getModerationsNeeded(Field $field, Record $record) {
        return array();
    }


    public function getLabel() {
        return "Boolean";
    }

    public function isMultipleType() {
        return false;
    }

    public function getEditFieldForm( Field $field, Record $record ) {

        $dataHasField = $this->getLatestFieldValue($field, $record);

        return new RecordHasFieldBooleanValueType($dataHasField);
    }

    public function getEditFieldFormNewRecords( Field $field, Record $record, Event $event, $form, User $user = null, $approve = false ) {

        // TODO see if value has changed before saving!! Can return array() if not.

        $newRecordHasFieldValues = new RecordHasFieldBooleanValue();
        $newRecordHasFieldValues->setRecord($record);
        $newRecordHasFieldValues->setField($field);
        $newRecordHasFieldValues->setValue($form->get('value')->getData());
        $newRecordHasFieldValues->setCreationEvent($event);
        if ($approve) {
            $newRecordHasFieldValues->setApprovedAt(new \DateTime());
            $newRecordHasFieldValues->setApprovalEvent($event);
        }

        return array ($newRecordHasFieldValues);
    }

    public function getViewTemplate() {
        return '@Directoki/FieldType/Boolean/view.html.twig';
    }

    public function getAPIJSON( Field $field, Record $record ) {
        $latest = $this->getLatestFieldValue($field, $record);
        return array('value'=>$latest->getValue());
    }


    public function processAPI1Record(Field $field, Record $record = null, ParameterBag $parameterBag, Event $event) {
        if ($parameterBag->has('field_'.$field->getPublicId().'_value')) {
            $currentValue = '';
            if ( $record !== null ) {
                $latestValueObject = $this->getLatestFieldValue($field, $record);
                $currentValue = $latestValueObject->getValue();
            }
            $newValue = in_array(trim(strtolower($parameterBag->get('field_'.$field->getPublicId().'_value'))), array('1','y','yes','t','true'));
            if ($newValue != $currentValue) {
                $newRecordHasFieldValues = new RecordHasFieldBooleanValue();
                $newRecordHasFieldValues->setRecord($record);
                $newRecordHasFieldValues->setField($field);
                $newRecordHasFieldValues->setValue($newValue);
                $newRecordHasFieldValues->setCreationEvent($event);
                return array($newRecordHasFieldValues);
            }
        }
        return array();
    }

}
