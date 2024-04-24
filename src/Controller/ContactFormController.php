<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactFormForm;
use App\Form\Type\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactFormController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/kontakt', name: 'contact_form')]
    public function contactFormAction(Request $request): Response
    {
        $contactFormForm = new ContactFormForm();
        $form = $this->createForm(
            ContactFormType::class,
            $contactFormForm
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactEmailSent = $this->sendContactEmail($contactFormForm);

            return $this->render(
                'contact_form/send_message_info.html.twig',
                [
                    'activeMenu' => 'contact_form',
                    'contactEmailSent' => $contactEmailSent
                ]
            );
        }

        return $this->render('contact_form/contact_form.html.twig', [
            'activeMenu' => 'contact_form',
            'form' => $form->createView()
        ]);
    }

    private function sendContactEmail(ContactFormForm $form): bool
    {
        $emailFrom = $form->getEmail();
        $emailTo = $this->getParameter('admin_email');
        $subject = $form->getSubject();
        $text = $form->getMessage();
        $message = (new Email())
            ->from($emailFrom)
            ->to($emailTo)
            ->subject($subject)
            ->html(
                $this->renderView(
                    'send_email/send_email.html.twig',
                    [
                        'emailFrom' => $emailFrom,
                        'emailTo' => $emailTo,
                        'subject' => $subject,
                        'text' => $text
                    ]
                )
            )
        ;

        return (bool) $this->mailer->send($message);
    }
}
