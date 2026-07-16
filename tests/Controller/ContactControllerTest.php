<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Me joindre');
        self::assertSelectorExists('form input[name="contact[contact]"]');
    }

    public function testInvalidContactIsRejected(): void
    {
        $client = static::createClient();
        $this->submitContactForm($client, 'ni-un-email-ni-un-telephone', 'Bonjour');

        self::assertResponseStatusCodeSame(422); // formulaire ré-affiché, pas de redirection
        self::assertEmailCount(0);
    }

    public function testNationalPhoneFormatIsRejected(): void
    {
        // La contrainte téléphone ne fixe pas de région par défaut :
        // seul le format international (+33…) est accepté.
        $client = static::createClient();
        $this->submitContactForm($client, '0612345678', 'Bonjour');

        self::assertResponseStatusCodeSame(422);
        self::assertEmailCount(0);
    }

    public function testEmptyMessageIsRejected(): void
    {
        $client = static::createClient();
        $this->submitContactForm($client, 'contact@example.com', '');

        self::assertResponseStatusCodeSame(422);
        self::assertEmailCount(0);
    }

    public function testValidEmailContactSendsMessage(): void
    {
        $client = static::createClient();
        $this->submitContactForm($client, 'contact@example.com', 'Bonjour, votre profil m\'intéresse.');

        self::assertResponseRedirects('/');
        self::assertEmailCount(1);

        $email = self::getMailerMessage();
        self::assertEmailAddressContains($email, 'to', 'sebastien.glorian@zohomail.eu');
        self::assertEmailTextBodyContains($email, 'contact@example.com');

        $client->followRedirect();
        self::assertSelectorTextContains('body', 'Votre message a été transmis');
    }

    public function testValidInternationalPhoneContactSendsMessage(): void
    {
        $client = static::createClient();
        $this->submitContactForm($client, '+33612345678', 'Bonjour, pouvez-vous me rappeler ?');

        self::assertResponseRedirects('/');
        self::assertEmailCount(1);
    }

    private function submitContactForm(KernelBrowser $client, string $contact, string $message): Crawler
    {
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->filter('form')->form([
            'contact[contact]' => $contact,
            'contact[message]' => $message,
        ]);

        return $client->submit($form);
    }
}
