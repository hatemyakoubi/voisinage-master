<?php
namespace App\Controller;

use App\Data\SearchCandidat;
use App\Form\SearchCandidatFormType;
use App\Repository\UserImageRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CandidatController extends AbstractController
{
    /**
     * @Route("/candidat", name="candidat", methods={"GET"})
     */
    public function Index(UserRepository $candidatsRepository, PaginatorInterface $paginator, Request $request, UserImageRepository $userimg):Response
    {
      

        //$img = $userimg->findAll(['updatedAt'=>'DESC'], ['limit'=>1]);
        $user = $candidatsRepository->findAll();

        
        
      // $donnees =  $candidatsRepository->findAll();
         $candidats = $paginator->paginate(
            $user, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        
        return $this->render('home/candidats.html.twig',[
            'candidats'=> $candidats,
            
             'controller_name'=> 'Candidats'
        ]);
    }
   
}
