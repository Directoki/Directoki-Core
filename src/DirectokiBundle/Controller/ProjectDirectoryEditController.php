<?php

namespace DirectokiBundle\Controller;

use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Field;
use DirectokiBundle\Entity\Record;
use DirectokiBundle\Entity\RecordHasState;
use DirectokiBundle\FieldType\BooleanFieldType;
use DirectokiBundle\FieldType\FieldTypeBoolean;
use DirectokiBundle\FieldType\FieldTypeEmail;
use DirectokiBundle\FieldType\FieldTypeLatLng;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\FieldType\FieldTypeURL;
use DirectokiBundle\FieldType\LatLngFieldType;
use DirectokiBundle\FieldType\StringFieldType;
use DirectokiBundle\FieldType\TextFieldType;
use DirectokiBundle\Form\Type\BooleanFieldNewType;
use DirectokiBundle\Form\Type\FieldNewBooleanType;
use DirectokiBundle\Form\Type\FieldNewEmailType;
use DirectokiBundle\Form\Type\FieldNewLatLngType;
use DirectokiBundle\Form\Type\FieldNewStringType;
use DirectokiBundle\Form\Type\FieldNewTextType;
use DirectokiBundle\Form\Type\FieldNewURLType;
use DirectokiBundle\Form\Type\RecordNewType;
use DirectokiBundle\Form\Type\StringFieldNewType;
use DirectokiBundle\Form\Type\TextFieldNewType;
use DirectokiBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class ProjectDirectoryEditController extends ProjectDirectoryController
{

    protected function build($projectId, $directoryId) {
        parent::build($projectId, $directoryId);
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newStringField.html.twig', array(
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newStringField.html.twig', array(
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newStringField.html.twig', array(
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newTextField.html.twig', array(
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newBooleanField.html.twig', array(
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

                return $this->redirect($this->generateUrl('directoki_project_directory_fields', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newLatLngField.html.twig', array(
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

        $form = $this->createForm(new RecordNewType());
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
                $event->setAPIVersion(1);

                $record = new Record();
                $record->setCachedState(RecordHasState::STATE_DRAFT);
                $record->setDirectory($this->directory);
                $record->setCreationEvent($event);

                $doctrine->persist($record);
                $doctrine->persist($event);
                $doctrine->flush(array($event, $record));

                return $this->redirect($this->generateUrl('directoki_project_directory_record_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId(),
                    'recordId'=>$record->getPublicId(),
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryEdit:newRecord.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'form' => $form->createView(),
        ));

    }

}