<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Sébastien Glorian');
    }

    public function testHomePageShowsTitleAndSourceLink(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertStringContainsString('Développeur back-end senior', $crawler->text());
        self::assertGreaterThan(
            0,
            $crawler->filter('a[href="https://github.com/perlbag/curriculum"]')->count(),
            'Le footer doit pointer vers le code source du site.'
        );
    }
}
