<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExperimentControllerTest extends WebTestCase
{
    public function testExperimentsPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/experimentations');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Expérimentations');
    }

    public function testNavigationLinksToExperiments(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertGreaterThan(
            0,
            $crawler->filter('header a[href$="/experimentations"]')->count(),
            'La navigation doit mener au journal des expérimentations.'
        );
    }
}
