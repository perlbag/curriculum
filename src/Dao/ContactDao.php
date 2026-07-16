<?php

namespace App\Dao;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\PhoneNumber;

final class ContactDao
{
	#[Assert\NotBlank(message: "contact.notblank")]
	#[Assert\AtLeastOneOf([
		new Assert\Email(message: "contact.email"),
		new PhoneNumber(defaultRegion: 'FR'),
	])]
	public string $contact;

	#[Assert\NotBlank(message: "message.notblank")]
	public string $message;
}
