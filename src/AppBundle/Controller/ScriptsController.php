<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/04/2017
 * Time: 17:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Script;
use AppBundle\Form\Type\ScriptType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\VarDumper\VarDumper;


class ScriptsController extends Controller
{
    /**
     * @Route("/project/{pid}/script/insert/{readme}", name="script_insert")
     */
    public function insertAction(Request $request, $pid, $readme = false) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $script = new Script();
        $script->setTitle($readme ? 'README.md' : 'Untitled');
        $script->setProject($project);

        $em->persist($script);
        $em->flush();

        return $this->redirectToRoute('script_update', [
            'pid' => $project->getId(),
            'id' => $script->getId()
        ]);
    }

    /**
     * @Route("/project/{pid}/script/{id}", name="script_get")
     */
    public function getAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $script = $em->getRepository('AppBundle:Script')->find($id);

        if(!$script || $script->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        return $this->render('@App/script/get.html.twig', array(
            'project' => $project,
            'script' => $script
        ));
    }

    /**
     * @Route("/project/{pid}/script/{id}/update", name="script_update")
     */
    public function updateAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $script = $em->getRepository('AppBundle:Script')->find($id);

        if(!$script || $script->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        $form = $this->createForm(ScriptType::class, $script);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($script);
                $em->flush();

                return $this->redirectToRoute('project_get', [
                    'id' => $project->getId()
                ]);
            }
        }

        return $this->render('@App/script/update.html.twig', array(
            'form' => $form->createView(),
            'project' => $project,
            'script' => $script
        ));
    }

    /**
     * @Route("/project/{pid}/script/{id}/delete", name="script_delete")
     */
    public function deleteAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $script = $em->getRepository('AppBundle:Script')->find($id);

        if(!$script || $script->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        $em->remove($script);
        $em->flush();

        return $this->redirectToRoute('project_get', [
            'id' => $project->getId()
        ]);
    }
}