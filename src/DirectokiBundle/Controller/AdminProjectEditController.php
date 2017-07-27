<?php

namespace DirectokiBundle\Controller;

use DirectokiBundle\Entity\Directory;
use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Locale;
use DirectokiBundle\Form\Type\DirectoryNewType;
use DirectokiBundle\Form\Type\LocaleNewType;
use DirectokiBundle\Security\ProjectVoter;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class AdminProjectEditController extends AdminProjectController
{



    protected function build($projectId) {
        parent::build($projectId);
        $this->denyAccessUnlessGranted(ProjectVoter::ADMIN, $this->project);
        if ($this->container->getParameter('directoki.read_only')) {
            throw new HttpException(503, 'Directoki is in Read Only mode.');
        }
    }


    public function newDirectoryAction($projectId)
    {

        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $directory = new Directory();
        $directory->setProject($this->project);

        $form = $this->createForm(new DirectoryNewType(), $directory);
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

                $directory->setCreationEvent($event);
                $doctrine->persist($directory);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_directory_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'directoryId'=>$directory->getPublicId()
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectEdit:newDirectory.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }

    public function newLocaleAction($projectId)
    {

        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $locale = new Locale();
        $locale->setProject($this->project);

        $form = $this->createForm(new LocaleNewType(), $locale);
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

                $locale->setCreationEvent($event);
                $doctrine->persist($locale);

                $doctrine->flush();

                return $this->redirect($this->generateUrl('directoki_admin_project_locale_list', array(
                    'projectId'=>$this->project->getPublicId(),
                )));
            }
        }


        return $this->render('DirectokiBundle:AdminProjectEdit:newLocale.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }



}
