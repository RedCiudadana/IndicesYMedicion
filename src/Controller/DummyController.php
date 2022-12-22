<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/dummy", name="app_dummy")
     */
    public function index(): Response
    {
        return $this->render('dummy/index.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }

    /**
     * @Route("/home", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('dummy/home.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }
}
