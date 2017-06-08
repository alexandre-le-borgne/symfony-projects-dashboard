<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 08/04/2017
 * Time: 17:52
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Project;
use AppBundle\Form\Type\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjectsController extends Controller
{
    /**
     * @Route("/project/insert", name="project_insert")
     */
    public function insertAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // perform some action, such as save the object to the database
                $em->persist($project);
                $em->flush();

                return $this->redirectToRoute('project_get', [
                    'id' => $project->getId()
                ]);
            }
        }

        return $this->render('@App/projects/insert.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/project/{id}", name="project_get")
     */
    public function getAction($id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($id);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        return $this->render('@App/projects/get.html.twig', array(
            'project' => $project
        ));
    }

    /**
     * @Route("/project/{id}/update", name="project_update")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($id);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ProjectType::class, $project);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                // perform some action, such as save the object to the database
//                $em->persist($project);
                $em->flush();

                return $this->redirectToRoute('project_get', [
                    'id' => $id
                ]);
            }
        }

        return $this->render('@App/projects/update.html.twig', array(
            'form' => $form->createView(),
            'project' => $project
        ));
    }

    /**
     * @Route("/project/{id}/delete", name="project_delete")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($id);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('index');
    }
}