<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/index", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('dummy/index.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(): Response
    {
        return $this->render('dummy/login.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('dummy/about.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }

    /**
     * @Route("/metodologia", name="app_metodologia")
     */
    public function metodologia(): Response
    {
        return $this->render('dummy/metodologia.html.twig', [
            'controller_name' => 'DummyController',
        ]);
    }
}
