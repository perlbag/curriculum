<?php

namespace App\Controller;

use App\Form\Type\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
	public function __construct(
		private \Symfony\Component\Mailer\MailerInterface $mailer,
		private \Symfony\Component\HttpFoundation\RequestStack $requestStack,
	) {
	}

	#[Route('/contact', name: 'app_contact')]
	public function index(): Response
	{
		$form = $this->createForm(ContactType::class);

		$form->handleRequest($this->requestStack->getCurrentRequest());

		if ($form->isSubmitted() && $form->isValid()) {
			$dao = $form->getData();

            $mail = (new Email())
                ->addFrom("contact@glorian.ovh")
                ->addTo("sebastien.glorian@zohomail.eu")
                ->subject("[glorian.ovh] Nouvelle demande")
                ->text($this->renderView("email/contact-text.html.twig", ["dao" => $dao]))
                ->html($this->renderView("email/contact.html.twig", ["dao" => $dao]));
            $this->mailer->send($mail);

			$this->addFlash("success", "Votre message a été transmis. J'y réponds au plus vite");

			return $this->redirectToRoute("app_home");
		}

		return $this->render('contact/index.html.twig', [
			"form" => $form,
		]);
	}
}