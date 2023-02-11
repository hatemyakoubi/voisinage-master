<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *  @IsGranted("ROLE_ADMIN")
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
             'controller_name'=> 'Listes des utilisateurs'
        ]);
    }
  
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled('1');
           
              $encoded = $passwordEncoder->encodePassword($user, $form->get("password")->getData());

             $user->setPassword($encoded);
            $user->setToken(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
             'controller_name'=> 'Ajouter un utilisateur'
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'user' => $user,
             'controller_name'=> 'Détail d\'utilisateur'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'user' => $user,
            'form' => $form->createView(),
             'controller_name'=> 'Modifier un utilisateur'
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
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
