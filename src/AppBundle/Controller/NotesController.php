<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/04/2017
 * Time: 17:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Note;
use AppBundle\Form\Type\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\VarDumper\VarDumper;


class NotesController extends Controller
{
    /**
     * @Route("/project/{pid}/note/insert/{readme}", name="note_insert")
     */
    public function insertAction(Request $request, $pid, $readme = false) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $note = new Note();
        $note->setTitle($readme ? 'README.md' : 'Untitled');
        $note->setProject($project);

        $em->persist($note);
        $em->flush();

        return $this->redirectToRoute('note_update', [
            'pid' => $project->getId(),
            'id' => $note->getId()
        ]);
    }

    /**
     * @Route("/project/{pid}/note/{id}", name="note_get")
     */
    public function getAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $note = $em->getRepository('AppBundle:Note')->find($id);

        if(!$note || $note->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        return $this->render('@App/note/get.html.twig', array(
            'project' => $project,
            'note' => $note
        ));
    }

    /**
     * @Route("/project/{pid}/note/{id}/update", name="note_update")
     */
    public function updateAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $note = $em->getRepository('AppBundle:Note')->find($id);

        if(!$note || $note->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        $form = $this->createForm(NoteType::class, $note);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($note);
                $em->flush();

                return $this->redirectToRoute('project_get', [
                    'id' => $project->getId()
                ]);
            }
        }

        return $this->render('@App/note/update.html.twig', array(
            'form' => $form->createView(),
            'project' => $project,
            'note' => $note
        ));
    }

    /**
     * @Route("/project/{pid}/note/{id}/delete", name="note_delete")
     */
    public function deleteAction(Request $request, $pid, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->find($pid);

        if(!$project) {
            return $this->redirectToRoute('index');
        }

        $note = $em->getRepository('AppBundle:Note')->find($id);

        if(!$note || $note->getProject()->getId() != $project->getId()) {
            return $this->redirectToRoute('project_get', ['id' => $project->getId()]);
        }

        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute('project_get', [
            'id' => $project->getId()
        ]);
    }
}