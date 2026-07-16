<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExperimentController extends AbstractController
{
    #[Route('/experimentations', name: 'app_experiments')]
    public function index(): Response
    {
        return $this->render('experiments/index.html.twig');
    }
}
