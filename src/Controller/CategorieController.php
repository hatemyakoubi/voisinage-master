<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\CategorieSousType;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'controller_name'=> 'Listes des catégories'
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form->createView(),
            'controller_name'=> 'Nouvelle catégories'
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        return $this->render('categorie/show.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'controller_name'=> 'Affiche une catégories'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form->createView(),
            'controller_name'=> 'Modifier une catégories'
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
    /**
     * @Route("/{id}/add-souscategories", name="add-souscategories" ,methods={"GET","POST"})
     * @param Request $request
     * @param CategorieRepository $categorieRepository
     * @param $id
     */
    public function AddSousCategories(Request $request, $id,CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository)
    {
        $categorie = $categorieRepository->find($id);   
        $form = $this->createForm(CategorieSousType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();
          return $this->redirectToRoute('categorie_show', ['id' => $categorie->getId()]);

       }
        return $this->render('categorie/affect.html.twig', [
            'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
             'demandes'=> $demandeRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form->createView(),
            'controller_name' => 'Affecter une sous categorie',
        ]);
    }
}
