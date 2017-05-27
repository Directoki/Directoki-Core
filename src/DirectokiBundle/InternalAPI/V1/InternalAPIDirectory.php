<?php

namespace DirectokiBundle\InternalAPI\V1;

use DirectokiBundle\Entity\Directory;
use DirectokiBundle\Entity\Project;

use DirectokiBundle\Entity\RecordHasState;
use DirectokiBundle\FieldType\FieldTypeEmail;
use DirectokiBundle\FieldType\FieldTypeLatLng;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueEmail;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueEmailEdit;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueLatLng;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueLatLngEdit;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueString;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueStringEdit;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueText;
use DirectokiBundle\InternalAPI\V1\Model\FieldValueTextEdit;
use DirectokiBundle\InternalAPI\V1\Model\Record;
use DirectokiBundle\InternalAPI\V1\Model\RecordCreate;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class InternalAPIDirectory
{

    protected $container;

    /** @var  Project */
    protected $project;


    /** @var  Directory */
    protected $directory;



    function __construct($container, Project $project, Directory $directory)
    {
        $this->container = $container;
        $this->project = $project;
        $this->directory = $directory;
    }

    /**
     * @param $recordId
     * @return InternalAPIRecord
     * @throws \Exception
     */
    function getRecordAPI( $recordId ) {
        $doctrine = $this->container->get('doctrine')->getManager();

        $record = $doctrine->getRepository('DirectokiBundle:Record')->findOneBy(array('directory'=>$this->directory, 'publicId'=>$recordId));
        if (!$record) {
            throw new \Exception("Not Found Record");
        }

        return new InternalAPIRecord($this->container, $this->project, $this->directory, $record);
    }


    /**
     * @param $fieldId
     * @return InternalAPIField
     * @throws \Exception
     */
    function getFieldAPI( $fieldId ) {
        $doctrine = $this->container->get('doctrine')->getManager();

        $field = $doctrine->getRepository('DirectokiBundle:Field')->findOneBy(array('directory'=>$this->directory, 'publicId'=>$fieldId));
        if (!$field) {
            throw new \Exception("Not Found Field");
        }

        return new InternalAPIField($this->container, $this->project, $this->directory, $field);
    }


    function getPublishedRecords() {

        $doctrine = $this->container->get('doctrine')->getManager();

        // Get data, return
        $out = array();
        $fields = $doctrine->getRepository('DirectokiBundle:Field')->findForDirectory($this->directory);

        foreach($doctrine->getRepository('DirectokiBundle:Record')->findBy(array('directory'=>$this->directory,'cachedState'=>RecordHasState::STATE_PUBLISHED)) as $record) {

            $fieldValues = array();
            foreach($fields as $field) {
                $fieldType = $this->container->get( 'directoki_field_type_service' )->getByField( $field );
                $tmp       = $fieldType->getLatestFieldValuesFromCache( $field, $record );

                if ( $field->getFieldType() == FieldTypeString::FIELD_TYPE_INTERNAL && $tmp[0] ) {
                    $fieldValues[ $field->getPublicId() ] = new FieldValueString( $field->getPublicId(), $field->getTitle(), $tmp[0]->getValue() );
                } else if ( $field->getFieldType() == FieldTypeText::FIELD_TYPE_INTERNAL && $tmp[0] ) {
                    $fieldValues[ $field->getPublicId() ] = new FieldValueText( $field->getPublicId(), $field->getTitle(), $tmp[0]->getValue() );
                } else if ( $field->getFieldType() == FieldTypeEmail::FIELD_TYPE_INTERNAL && $tmp[0] ) {
                    $fieldValues[ $field->getPublicId() ] = new FieldValueEmail( $field->getPublicId(), $field->getTitle(), $tmp[0]->getValue() );
                } else if ( $field->getFieldType() == FieldTypeLatLng::FIELD_TYPE_INTERNAL && $tmp[0] ) {
                    $fieldValues[ $field->getPublicId() ] = new FieldValueLatLng( $field->getPublicId(), $field->getTitle(), $tmp[0]->getLat(), $tmp[0]->getLng()  );
                }
            }
            $out[] = new Record($this->project->getPublicId(), $this->directory->getPublicId(), $record->getPublicId(), $fieldValues);
        }

        return $out;

    }

    function getRecordCreate() {

        $doctrine = $this->container->get('doctrine')->getManager();

        $fields = array();
        foreach($doctrine->getRepository('DirectokiBundle:Field')->findForDirectory($this->directory) as $field) {

            if ($field->getFieldType() == FieldTypeString::FIELD_TYPE_INTERNAL) {
                $fields[$field->getPublicId()] = new FieldValueStringEdit(null, $field);
            } else if ($field->getFieldType() == FieldTypeText::FIELD_TYPE_INTERNAL) {
                $fields[$field->getPublicId()] = new FieldValueTextEdit(null, $field);
            } else if ($field->getFieldType() == FieldTypeEmail::FIELD_TYPE_INTERNAL) {
                $fields[$field->getPublicId()] = new FieldValueEmailEdit(null, $field);
            } else if ($field->getFieldType() == FieldTypeLatLng::FIELD_TYPE_INTERNAL) {
                $fields[$field->getPublicId()] = new FieldValueLatLngEdit(null, $field);
            }
        }

        return new RecordCreate($this->project->getPublicId(), $this->directory->getPublicId(), $fields);
    }

    function saveRecordCreate(RecordCreate $recordCreate, Request $request = null)
    {

        $doctrine = $this->container->get('doctrine')->getManager();

        if ($recordCreate->getProjectPublicId() != $this->project->getPublicId()) {
            throw new \Exception('Passed wrong project!');
        }
        if ($recordCreate->getDirectoryPublicId() != $this->directory->getPublicId()) {
            throw new \Exception('Passed wrong Directory!');
        }


        $event = $this->container->get('directoki_event_builder_service')->build(
            $this->project,
            $recordCreate->getUser(),
            $request,
            $recordCreate->getComment()
        );

        $fieldDataToSave = array();
        foreach ( $recordCreate->getFieldValueEdits() as $fieldEdit ) {

            $field = $doctrine->getRepository('DirectokiBundle:Field')->findOneBy(array('directory'=>$this->directory, 'publicId'=>$fieldEdit->getPublicID()));

            $fieldType = $this->container->get( 'directoki_field_type_service' )->getByField( $field );

            $fieldDataToSave = array_merge($fieldDataToSave, $fieldType->processInternalAPI1Record($fieldEdit, $this->directory, null, $event));

        }

        if ($fieldDataToSave) {

            $email = $recordCreate->getEmail();
            if ($email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $event->setContact( $doctrine->getRepository( 'DirectokiBundle:Contact' )->findOrCreateByEmail($this->project, $email));
                } else {
                    $this->get('logger')->error('An edit on project '.$this->project->getPublicId().' directory '.$this->directory->getPublicId().' new record had an email address we did not recognise: ' . $email);
                }
            }
            $doctrine->persist($event);

            $record = new \DirectokiBundle\Entity\Record();
            $record->setDirectory($this->directory);
            $record->setCreationEvent($event);
            $record->setCachedState(RecordHasState::STATE_DRAFT);
            $doctrine->persist($record);

            // Also record a request to publish this record but don't approve it - moderator will do that.
            $recordHasState = new RecordHasState();
            $recordHasState->setRecord($record);
            $recordHasState->setState(RecordHasState::STATE_PUBLISHED);
            $recordHasState->setCreationEvent($event);
            $doctrine->persist($recordHasState);

            foreach($fieldDataToSave as $entityToSave) {
                $entityToSave->setRecord($record);
                $doctrine->persist($entityToSave);
            }

            $doctrine->flush();

            return true;

        } else {
            return false;
        }

    }



}