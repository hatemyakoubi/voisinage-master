<?php

namespace App\Controller;

use App\Repository\CommentsRepository;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="administration")
     */
    public function AdminIndex(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository):Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin.html.twig',[
                'users'=> $userRepository->findAll(),
                'offres'=> $offreRepository->findAll(),
                'demandes'=> $demandeRepository->findAll(),
                'comments'=> $commentsRepository->findAll(),

                'controller_name'=> 'Tableau de bord'
        ]);
    }
    

}
