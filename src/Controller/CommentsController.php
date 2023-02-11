<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\Comments1Type;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    /**
     * @Route("/", name="comments_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('comments/index.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des commentaires'
        ]);
    }

    /**
     * @Route("/new", name="comments_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $comment = new Comments();
        $form = $this->createForm(Comments1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comments_index');
        }

        return $this->render('comments/new.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'comment' => $comment,
            'form' => $form->createView(),
            'controller_name'=> 'Nouveau commentaire'
        ]);
    }

    /**
     * @Route("/{id}", name="comments_show", methods={"GET"})
     */
    public function show(Comments $comment,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('comments/show.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'comment' => $comment,
            'controller_name'=> 'Affiche Commentaire'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comments_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comments $comment,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(Comments1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
             $this->addFlash('success', 'Commentaire a été modifié avec succès');
            return $this->redirectToRoute('comments_index');
        }

        return $this->render('comments/edit.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'comment' => $comment,
            'form' => $form->createView(),
            'controller_name'=> 'Modifier commentaire'
        ]);
    }

    /**
     * @Route("/{id}", name="comments_delete", methods={"POST"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
         $this->addFlash('success', 'Commentaire a été supprimé avec succès');
        return $this->redirectToRoute('comments_index');
    }
}
