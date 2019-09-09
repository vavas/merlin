<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController extends AbstractController
{
    private $githubClient;

    /**
     * LoginController constructor.
     * @param $githubClient
     */
    public function __construct($githubClient)
    {
        $this->githubClient = $githubClient;
    }


    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/login/github", name="github")
     */
    public function github(UrlGeneratorInterface $generator)
    {
        $url = $generator->generate('dashboard', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        return new RedirectResponse("https://github.com/login/oauth/authorize?client_id=$this->githubClient&" .  $url);

    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('login/dashboard.html.twig', [
            'controller_name' => 'LoginController',
        ]);

    }


}
