<?php

namespace DirectokiBundle\Controller;

use DirectokiBundle\Action\UpdateRecordCache;
use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Field;
use DirectokiBundle\Entity\Record;
use DirectokiBundle\Entity\RecordHasState;
use DirectokiBundle\FieldType\FieldTypeBoolean;
use DirectokiBundle\FieldType\FieldTypeEmail;
use DirectokiBundle\FieldType\FieldTypeLatLng;
use DirectokiBundle\FieldType\FieldTypeMultiSelect;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\FieldType\FieldTypeURL;
use DirectokiBundle\Form\Type\FieldNewBooleanType;
use DirectokiBundle\Form\Type\FieldNewEmailType;
use DirectokiBundle\Form\Type\FieldNewLatLngType;
use DirectokiBundle\Form\Type\FieldNewStringType;
use DirectokiBundle\Form\Type\FieldNewTextType;
use DirectokiBundle\Form\Type\FieldNewURLType;
use DirectokiBundle\Form\Type\RecordNewType;
use DirectokiBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class AdminProjectDirectoryEditController extends AdminProjectDirectoryController
{

    protected function build($projectId, $directoryId) {
        parent::build($projectId, $directoryId);
        $this->denyAccessUnlessGranted(ProjectVoter::ADMIN, $this->project);
    }

    public function newStringFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeString::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewStringType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newStringField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }


    public function newEmailFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeEmail::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewEmailType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newEmailField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }


    public function newURLFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeURL::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewURLType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newURLField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }



    public function newTextFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeText::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewTextType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newTextField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }

    public function newBooleanFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeBoolean::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewBooleanType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newBooleanField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }

    public function newLatLngFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeLatLng::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewLatLngType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newLatLngField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }

    public function newMultiSelectFieldAction($projectId, $directoryId)
    {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $field = new Field();
        $field->setDirectory($this->directory);
        $field->setFieldType(FieldTypeMultiSelect::FIELD_TYPE_INTERNAL);

        $form = $this->createForm(new FieldNewLatLngType(), $field);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $doctrine->persist($event);

                $field->setSort($doctrine->getRepository('DirectokiBundle:Field')->getNextFieldSortValue($this->directory));
                $field->setCreationEvent($event);
                $doctrine->persist($field);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newMultiSelectField.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));


    }


    public function newRecordAction($projectId, $directoryId) {

        // build
        $this->build($projectId, $directoryId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $fields        = $doctrine->getRepository( 'DirectokiBundle:Field' )->findForDirectory( $this->directory );


        $form = $this->createForm(new RecordNewType($this->container, $fields));
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $approve = $form->get('approve')->getData();

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    null
                );
                $event->setAPIVersion(1);

                $record = new Record();
                $record->setCachedState($approve ? RecordHasState::STATE_PUBLISHED : RecordHasState::STATE_DRAFT);
                $record->setDirectory($this->directory);
                $record->setCreationEvent($event);

                $doctrine->persist($record);
                $doctrine->persist($event);

                if ($approve) {
                    $recordHasState = new RecordHasState();
                    $recordHasState->setRecord($record);
                    $recordHasState->setState(RecordHasState::STATE_PUBLISHED);
                    $recordHasState->setCreationEvent($event);
                    $recordHasState->setApprovedAt(new \DateTime());
                    $recordHasState->setApprovalEvent($event);
                    $doctrine->persist($recordHasState);
                }

                foreach($fields as $field) {
                    $fieldType = $this->container->get('directoki_field_type_service')->getByField($field);
                    foreach($fieldType->processNewRecordForm($field, $record, $form, $event, $approve) as $entity) {
                        $doctrine->persist($entity);
                    }
                }

                $doctrine->flush();

                if ($approve) {
                    $updateRecordCache = new UpdateRecordCache($this->container);
                    $updateRecordCache->go($record);
                }

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_record_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId(),
                    'recordId'=>$record->getPublicId(),
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectDirectoryEdit:newRecord.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
            'fields' => $fields,
            'fieldTypeService' => $this->container->get('directoki_field_type_service'),
        ));

    }

}
