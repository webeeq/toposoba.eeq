<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class ContactFormForm
{
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 180,
        maxMessage: 'form.contact_form.email.max_message'
    )]
    #[Assert\Email(message: 'form.contact_form.email.message')]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 180,
        maxMessage: 'form.contact_form.subject.max_message'
    )]
    private ?string $subject = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 10000,
        maxMessage: 'form.contact_form.message.max_message'
    )]
    private ?string $message = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
