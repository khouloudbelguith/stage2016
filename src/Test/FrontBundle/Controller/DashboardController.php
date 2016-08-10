<?php

namespace Test\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestFrontBundle:Dashboard:index.html.twig');
    }
}