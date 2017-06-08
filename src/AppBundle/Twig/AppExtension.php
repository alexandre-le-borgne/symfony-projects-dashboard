<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 08/04/2017
 * Time: 19:11
 */
namespace AppBundle\Twig;

use AppBundle\Entity\Note;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\VarDumper\VarDumper;

class AppExtension extends \Twig_Extension
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * AppExtension constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions() {
        return array(
            new \Twig_Function('getUsedTags', [$this, 'getUsedTags']),
            new \Twig_Function('getReadMe', [$this, 'getReadMe'])
        );
    }

    public function getUsedTags($ignoredTagEntities = []) {
        $ignoredTags = [];
        foreach ($ignoredTagEntities as $ignoredTagEntity) {
            $ignoredTags[] = $ignoredTagEntity->getName();
        }
        $results = $this->manager->getRepository('AppBundle:Tag')->findBy([], ['lastUseAt' => 'DESC'], 10);
        $tags = [];
        foreach ($results as $result) {
            if(in_array($result, $ignoredTags)) continue;
            $tags[] = $result->getName();
        }
        return $tags;
    }

    public function getReadMe($notes) {
        /**
         * @var Note $note
         */
        foreach ($notes as $note) {
            if($note->getTitle() === Note::READ_ME) return $note;
        }
        return false;
    }
}