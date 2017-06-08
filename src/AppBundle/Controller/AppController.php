<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $terms = $request->get('search', []);
        $projects = $em->getRepository('AppBundle:Project')->search($terms, 2);
        $notes = $em->getRepository('AppBundle:Note')->search($terms, 4);
        $scripts = $em->getRepository('AppBundle:Script')->search($terms, 4);

        if($request->getMethod() == 'POST') {
            $data = [
                'projects' => array_map(function($project) {
                    return $this->renderView('@App/projects/partial.html.twig', ['project' => $project]);
                }, $projects),
                'notes' => array_map(function($note) {
                    return $this->renderView('@App/partials/note.html.twig', ['note' => $note]);
                }, $notes),
                'scripts' => array_map(function($script) {
                    return $this->renderView('@App/partials/script.html.twig', ['script' => $script]);
                }, $scripts),
            ];
            return new JsonResponse($data);
        }

        return $this->render('@App/index.html.twig', [
            'projects' => $projects,
            'notes' => $notes,
            'scripts' => $scripts
        ]);
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('AppBundle:Project')->findAll();

        return $this->render('@App/projects.html.twig', [
            'projects' => $projects
        ]);
    }

}
