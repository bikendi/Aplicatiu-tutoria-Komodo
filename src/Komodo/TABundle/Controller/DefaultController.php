<?php

namespace Komodo\TABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KomodoTABundle:Default:index.html.twig');
    }
}
