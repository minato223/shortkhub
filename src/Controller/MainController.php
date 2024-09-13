<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController {

    #[Route('/', name: 'main')]
    public function index() {
        return $this->render('pages/home/home.html.twig');
    }
}