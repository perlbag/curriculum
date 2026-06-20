<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthController extends AbstractController
{
    // Sonde du healthcheck Swarm (sans dépendance externe).
    #[Route('/health', name: 'app_health', methods: ['GET'])]
    public function health(): Response
    {
        return new Response('ok', Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }
}
