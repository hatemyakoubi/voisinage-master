<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function sendEmail(MailerInterface $mailer , Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
           
            $email = (new Email())
            ->from($contact['email'])
            ->to('voisinage.web@gmail.com')
            ->subject($contact['subject'])
            ->text( $contact['message'] );
            /*->html( $contact['nom'],
                    ' <br>
                   <p>See Twig integration for better HTML integration!</p>
                    ');*/

        $mailer->send($email);
        
             $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
        }
        return $this->render('contact/contact.html.twig',[
            'form' => $form->createView(),
            'controller_name'=>'Contact'
            ]);
    }
}
