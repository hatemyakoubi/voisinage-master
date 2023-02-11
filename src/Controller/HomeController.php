<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Offre;
use App\Entity\Images;
use App\Entity\Demande;
use App\Form\OffreType;
use App\Data\SearchData;
use App\Entity\Comments;
use App\Data\SearchOffre;
use App\Form\CommentsType;
use App\Entity\CommentOffre;
use App\Form\SearchFormType;
use App\Form\SearchOffreType;
use App\Form\CommentOffreType;
use App\Form\PublierDemandeType;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\DemandeRepository;
use App\Repository\CommentsRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserImageRepository;
use App\Repository\SousCategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home",methods={"GET", "POST"})
     */
    public function index(CategorieRepository $categorieRepository, UserRepository $userRepository, OffreRepository $offreRepository, SousCategorieRepository $sousCategorieRepository): Response
    {
        $offres = $offreRepository->findBy(['publier'=>'true'], ['createdAt'=>'DESC'], 8);
        return $this->render('base.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'souscategories' => $sousCategorieRepository->findAll(),
            'offres'=>$offres,
            'users'=> $userRepository->findAll(),
            'controller_name' => 'Acceuil',
        ]);
    }
    /**
     * @Route("/{id}/verification", name="msgverif", methods={"GET","POST"})
     */
    public function msgverif(User $user): Response
    {
        return $this->render('home/msgverif.html.twig', [
            'user'=>$user,
            'controller_name' => 'Vérification',
        ]);
    }
    
    /**
     * @Route("/demande", name="demande")
     */
    public function demande(DemandeRepository $demandeRepository, Request $request,  PaginatorInterface $paginator):Response
    {
        
        $data = new SearchData();
        $data->page = $request->get('page', 1);
         $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);
             $donnees =  $demandeRepository->findSearch($data);
         $demandes = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
         if (!$donnees){
         
             $this->addFlash('success', 'Opppss!! Recherche introuvable .');
         }
      
         return $this->render('demande/demande.html.twig',[
                'demandes'=>$demandes,
                'form' => $form->createView(),
                'controller_name'=> 'Demandes'
        ]);
    }
    /**
     * @Route("/offres", name="offres")
     */
    public function offres(OffreRepository $OffreRepository, Request $request,  PaginatorInterface $paginator):Response
    {
          
        $data = new SearchOffre();
        $data->page = $request->get('page', 1);
         $form = $this->createForm(SearchOffreType::class, $data);
        $form->handleRequest($request);
         $donnees =  $OffreRepository->findSearch($data);
         $offres = $paginator->paginate(
         $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
         if (!$donnees){
         
             $this->addFlash('success', 'Opppss!! Recherche introuvable .');
         }
        return $this->render('offres/offres.html.twig', [
            'controller_name' => 'Offres',
            'offres'=>$offres,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/publier_demande", name="publier_demande", methods={"GET","POST"})
     */
    public function PublierDeamnde(Request $request):Response
    {
        $offre = new Demande();
        $form=$this->createForm(PublierDemandeType::class, $offre);
        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid())
         {
            $offre->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('confirme_demande');
        }

        return $this->render('home/publierDemande.html.twig',[
                'demande' => $offre,
                'form' => $form->createView(),
                'controller_name'=> 'Publier demande'
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/detail_demande", name="detail_demande", methods={"GET","POST"})
     */
    public function Detail_demande(DemandeRepository $demandeRepository, Request $request,$id, UserImageRepository $userimg):Response
    {
        $offre = $demandeRepository->find($id);
        //$user = $offre->getUser()->getId();
        //$img = $userimg->findBy(['user'=>$user],['updatedAt'=>'DESC'], ['limit'=>1]);
        
        //commentaires

         $comment = new Comments();
         $commentForm = $this->createForm(CommentsType::class, $comment);
         $commentForm->handleRequest($request);
         //traintement de formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setDemandes($offre);
            $comment->setUser($this->getUser());
            
            // on récupere le contenu de champs parantid
            
            $parentid = $commentForm->get('parentid')->getData();

            //on va cherche le commentaire correspondant

            $em = $this->getDoctrine()->getManager();
            if($parentid != null){
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }
           
            // on définit le parent

            $comment ->setParent($parent ?? null);

            $em -> persist($comment);
            $em ->flush();
            $this->addFlash('message', 'votre commentaire a bien été envoyé');
            return $this->redirectToRoute('detail_demande',['id'=>$offre->getId()]);
        }
      
        return $this->render('demande/detail_demande.html.twig',[
              'demande'=>$offre,
              //'img'=>$img,
              'commentForm'=>$commentForm->createView(), 
              'controller_name'=> 'Détail demande'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/confirme_demande", name="confirme_demande")
     */
    public function confirme_demande():Response
    {
        return $this->render('home/confirme_demande.html.twig',[
                'controller_name'=> 'Confirme demande'
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/publier_offre", name="publier_offre")
     */
    public function PublierOffre(Request $request):Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class,$offre);
        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid())
         {
               // On récupère les images transmises
    $images = $form->get('images')->getData();
    
    // On boucle sur les images
    foreach($images as $image){
        // On génère un nouveau nom de fichier
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        
        // On copie le fichier dans le dossier uploads en service.yaml
        $image->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        $img = new Images();
        $img->setName($fichier);
        $offre->addImage($img);
    }

            $offre->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('confirme_demande');
        }
        return $this->render('home/publieroffre.html.twig',[
                'form' => $form->createView(),
                'controller_name'=> 'Publier offre'
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/detail_offre", name="detail_offre", methods={"GET","POST"})
     */
    public function Detail_Offre(OffreRepository $offreRepository, Request $request,$id, UserImageRepository $userimg):Response
    {
        $offre = $offreRepository->find($id);
        //$user = $offre->getUser()->getId();
        //$img = $userimg->findBy(['user'=>$user],['updatedAt'=>'DESC'], ['limit'=>1]);
        
        //commentaires

         $comment = new CommentOffre();
         $commentForm = $this->createForm(CommentOffreType::class, $comment);
         $commentForm->handleRequest($request);
         //traintement de formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setOffres($offre);
            $comment->setUser($this->getUser());
            
            // on récupere le contenu de champs parantid
            
            $parentid = $commentForm->get('parentid')->getData();

            //on va cherche le commentaire correspondant

            $em = $this->getDoctrine()->getManager();
            if($parentid != null){
                $parent = $em->getRepository(CommentOffre::class)->find($parentid);
            }
           
            // on définit le parent

            $comment ->setParent($parent ?? null);

            $em -> persist($comment);
            $em ->flush();
            $this->addFlash('message', 'votre commentaire a bien été envoyé');
            return $this->redirectToRoute('detail_offre',['id'=>$offre->getId()]);
        }
      
        return $this->render('offres/detail_offre.html.twig',[
              'offre'=>$offre,
              //'img'=>$img,
              'commentForm'=>$commentForm->createView(), 
              'controller_name'=> 'Détail offre'
        ]);
    }

    /**
     * @Route("/a-propos", name="apropos")
     */
   public function Apropos(UserRepository $userRepository, OffreRepository $offreRepository, DemandeRepository $demandeRepository, CommentsRepository $commentsRepository): Response
   {
      return $this->render('home/apropos.html.twig',[
           'controller_name'=> 'A propos',
           'users' => $userRepository->findAll(),
            'offres'=> $offreRepository->findAll(),
              'comments'=> $commentsRepository->findAll(),
            'demandes' => $demandeRepository->findAll(),
      ]);
   }
    

    

}
