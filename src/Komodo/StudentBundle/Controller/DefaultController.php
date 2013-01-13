<?php

namespace Komodo\StudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KomodoStudentBundle:Default:index.html.twig', array('name' => $name));
    }
}
