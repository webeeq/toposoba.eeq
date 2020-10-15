<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactFormForm;
use App\Form\Type\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class ContactFormController extends AbstractController
{
    private \Swift_Mailer $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/kontakt", name="contact_form")
     */
    public function contactFormAction(Request $request): Response
    {
        $contactFormForm = new ContactFormForm();
        $form = $this->createForm(
            ContactFormType::class,
            $contactFormForm
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactEmail = $this->sendContactEmail($contactFormForm);

            return $this->render(
                'contact_form/send_message_info.html.twig',
                [
                    'activeMenu' => 'contact_form',
                    'contactEmail' => $contactEmail
                ]
            );
        }

        return $this->render('contact_form/contact_form.html.twig', [
            'activeMenu' => 'contact_form',
            'form' => $form->createView()
        ]);
    }

    private function sendContactEmail(ContactFormForm $form): int
    {
        $emailFrom = $form->getEmail();
        $emailTo = $this->getParameter('admin_email');
        $subject = $form->getSubject();
        $text = $form->getMessage();
        $message = (new \Swift_Message($subject))
            ->setFrom($emailFrom)
            ->setTo($emailTo)
            ->setBody(
                $this->renderView(
                    'send_email/send_email.html.twig',
                    [
                        'emailFrom' => $emailFrom,
                        'emailTo' => $emailTo,
                        'subject' => $subject,
                        'text' => $text
                    ]
                ),
                'text/html'
            )
        ;

        return $this->mailer->send($message);
    }
}
