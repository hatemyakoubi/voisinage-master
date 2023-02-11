<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserImage;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/inscription", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $userimage = new UserImage();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            );
            // definier une image par défaut
            $userimage->setImageName("userprofile.png");
            $user->setUserImage($userimage);
            $user->setToken($this->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->mailer->sendEmail($user->getEmail(), $user->getToken());
            $this->addFlash("success", "Inscription réussie !");
             return $this->redirectToRoute("msgverif", ['id'=>$user->getId()]);
        }

        return $this->render('registration/register1.html.twig', [
            'form' => $form->createView(),
            'controller_name'=> 'Inscrivze-Vous'
        ]);
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token)
    {
        $user = $this->userRepository->findOneBy(["token" => $token]);
        if($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Compte actif !");
            return $this->redirectToRoute("app_login");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('register');
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}