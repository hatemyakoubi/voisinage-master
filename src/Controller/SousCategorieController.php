<?php

namespace App\Controller;

use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/sous/categorie")
 */
class SousCategorieController extends AbstractController
{
    /**
     * @Route("/", name="sous_categorie_index", methods={"GET"})
     */
    public function index(SousCategorieRepository $sousCategorieRepository ,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('sous_categorie/index.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'sous_categories' => $sousCategorieRepository->findAll(),
            'controller_name'=> 'Listes des sous catégories'
        ]);
    }

    /**
     * @Route("/new", name="sous_categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sousCategorie);
            $entityManager->flush();

            return $this->redirectToRoute('sous_categorie_index');
        }

        return $this->render('sous_categorie/new.html.twig', [
             'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'sous_categorie' => $sousCategorie,
            'form' => $form->createView(),
            'controller_name'=> 'Nouvelle sous catégories'
        ]);
    }

    /**
     * @Route("/{id}", name="sous_categorie_show", methods={"GET"})
     */
    public function show(SousCategorie $sousCategorie,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('sous_categorie/show.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'sous_categorie' => $sousCategorie,
            'controller_name'=> 'Affiche un sous catégories'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sous_categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SousCategorie $sousCategorie,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sous_categorie_index');
        }

        return $this->render('sous_categorie/edit.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'sous_categorie' => $sousCategorie,
            'form' => $form->createView(),
            'controller_name'=> 'Modifier sous catégories'
        ]);
    }

    /**
     * @Route("/{id}", name="sous_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, SousCategorie $sousCategorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sousCategorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sousCategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sous_categorie_index');
    }
}
