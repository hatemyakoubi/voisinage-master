<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\Offre1Type;
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
 * @Route("/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="offre_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('offre/index.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des offres',
        ]);
    }

    /**
     * @Route("/new", name="offre_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(Offre1Type::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre a été ajouter avec succès');
            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/new.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des offres',
            'offre' => $offre,
            'form' => $form->createView(),
             'controller_name'=> 'Ajouter un offre',
        ]);
    }

    /**
     * @Route("/{id}", name="offre_show", methods={"GET"})
     */
    public function show(Offre $offre,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des offres',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offre $offre,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(Offre1Type::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Offre modifié avec succès');
            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des offres',
        ]);
    }

    /**
     * @Route("/{id}", name="offre_delete", methods={"POST"})
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Offres a été supprimé avec succès');

        return $this->redirectToRoute('offre_index');
    }
}
