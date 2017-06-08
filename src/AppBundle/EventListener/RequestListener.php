<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/04/2017
 * Time: 16:32
 */

namespace AppBundle\EventListener;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->doctrine->getConnection()->exec('PRAGMA foreign_keys = ON');
    }
}