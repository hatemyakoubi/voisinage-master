<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Form\DemandeType1;
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
 * @Route("/admin/demande")
 */
class DemandeController extends AbstractController
{
    /**
     * @Route("/", name="demande_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('demande/index.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'controller_name'=> 'Listes des demandes',
        ]);
    }

    /**
     * @Route("/new", name="demande_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid())
         {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demande);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur a été ajouté avec succès');
            return $this->redirectToRoute('demande_index');
        }
        return $this->render('demande/new.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'demande' => $demande,
            'form' => $form->createView(),
             'controller_name'=> 'Nouvelle demande',
        ]);
    }

    /**
     * @Route("/{id}", name="demande_show", methods={"GET"})
     */
    public function show(Demande $demande,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
       
        return $this->render('demande/show.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'demande' => $demande,
             'controller_name'=> 'Info demandes',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="demande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Demande $demande,UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(DemandeType1::class, $demande);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Demande modifié avec succès');
            return $this->redirectToRoute('demande_index');
        }

        return $this->render('demande/edit.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
            'demande' => $demande,
            'form' => $form->createView(),
             'controller_name'=> 'Modifier demandes',
        ]);
    }

    /**
     * @Route("/{id}", name="demande_delete", methods={"POST"})
     */
    public function delete(Request $request, Demande $demande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demande);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Demande a été supprimé avec succès');
        return $this->redirectToRoute('demande_index');
    }
    
}
