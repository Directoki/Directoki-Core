<?php

namespace DirectokiBundle\Controller;
use DirectokiBundle\Entity\Event;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class ProjectDirectoryRecordFieldEditController extends ProjectDirectoryRecordFieldController
{

    protected function build($projectId, $directoryId, $recordId, $fieldId) {
        parent::build($projectId, $directoryId, $recordId, $fieldId);
        //$this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);
    }


    public function editAction($projectId, $directoryId, $recordId, $fieldId)
    {

        // build
        $this->build($projectId, $directoryId, $recordId, $fieldId);
        //data
        $doctrine = $this->getDoctrine()->getManager();

        $fieldType = $this->container->get('directoki_field_type_service')->getByField($this->field);

        $form = $this->createForm($fieldType->getEditFieldForm($this->field, $this->record));
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                // TODO see if value has changed before saving!!

                $event = $this->get('directoki_event_builder_service')->build(
                    $this->project,
                    $this->getUser(),
                    $this->getRequest(),
                    $form->get('createdComment')->getData()
                );
                $doctrine->persist($event);

                $newRecordHasFieldValues = $fieldType->getEditFieldFormNewRecords($this->field, $this->record, $event, $form, $this->getUser(), $form->get('approve')->getData());
                foreach($newRecordHasFieldValues as $newRecordHasFieldValue) {
                    $doctrine->persist( $newRecordHasFieldValue );
                }

                $doctrine->flush();
                return $this->redirect($this->generateUrl('directoki_project_directory_record_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$this->directory->getPublicId(),
                    'recordId'=>$this->record->getPublicId(),
                )));
            }
        }


        return $this->render('DirectokiBundle:ProjectDirectoryRecordFieldEdit:edit.html.twig', array(
            'project' => $this->project,
            'directory' => $this->directory,
            'record' => $this->record,
            'field' => $this->field,
            'form' => $form->createView(),
        ));

    }



}
