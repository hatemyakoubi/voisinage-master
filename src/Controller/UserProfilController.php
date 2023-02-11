<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Offre;
use App\Entity\Images;
use App\Form\UserType;
use App\Entity\Demande;
use App\Entity\UserImage;
use App\Form\UserEditType;
use App\Form\UserImageType;
use App\Form\OffreUserEditType;
use App\Form\DemandeUserEditType;
use App\Form\UserPofileImageType;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use App\Repository\ImagesRepository;
use App\Repository\DemandeRepository;
use App\Repository\UserImageRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserProfilController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil/{id}", name="user_profil")
     */
    public function profil(UserRepository $userRepository, ImagesRepository $offreimg ,$id, OffreRepository $offreRepository): Response
    {
     
        //$img = $userimg->findBy(['user'=>$id],['updatedAt'=>'DESC'], ['limit'=>1]);
        $userprofil = $userRepository->find($id);
       // $offre = $offreRepository->find(['user'=>$userprofil]);

       //dd($img);

        return $this->render('profile.html.twig', [
            'user' => $userprofil,
           // 'offres'=>$offreimags,
            'controller_name'=> 'Profile'
            //'img'=>$img
        ]);
    }

    /**
    * @Route("/edit-image/{id}", name="edit_image", methods={"GET","POST"})
    */
    public function EditProfilImage(Request $request, UserRepository $userRepository, $id):Response
    {
        $userimage = new UserImage();
        $user = $userRepository->find($id);
        //$userimage = new UserImage() ; 
       $img = $user->getUserImage();
        //$userimage->setUserImages($user);
       
        $form = $this->createForm(UserPofileImageType::class, $img);

        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
           //dd($form->getData());
           // $userimage->setUserImages($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'votre image à été bien modifier ');

            return $this->redirectToRoute('user_profil', ['id' =>$this->getUser()->getId()]);
        }
         return $this->render('user_profil/editimage.html.twig', [
            'userimage' => $user,
            'form' => $form->createView(),
            'controller_name'=> 'Modifier votre image'
        ]);

    }
    /**
     * @Route("/modifier-profile/{id}", name="edit_profile",methods={"GET","POST"})
     */
    public function EditProfil(Request $request,UserRepository $userRepository ,$id):Response
    {
            $user = $userRepository->find($id);
           $form = $this->createForm(UserEditType::class, $user);
            $form->handleRequest($request);
             
            if ($form->isSubmitted()) {
               
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'vos coordonnées à été bien modifier ');
            return $this->redirectToRoute('user_profil', ['id' =>$this->getUser()->getId()]);
             }

         return $this->render('user_profil/editprofil.html.twig', [
           'user' => $user,
           'form' => $form->createView(),
            'controller_name'=> 'Modifier votre profile'
        ]);
    }
    /**
     * @Route("/Vos-demandes/{id}", name="mes_demande",methods={"GET","POST"}  )
     */
    public function MesDemandes(Request $request,UserRepository $userRepository ,$id, DemandeRepository $demandeRepository):Response
    {
        $user = $userRepository->find($id);
        $demandes = $demandeRepository->find($user->getId());

       return $this->render('user_profil/MesDemandes.html.twig', [
           'user' => $user,
           'demande'=>$demandes,
            'controller_name'=> 'Vos demandes'
             ]);
    }
    /**
     * @Route("/Supprimer-demande-User/{id}", name="supprime_user_demande",methods={"GET","POST"}  )
     */
    public function delete(Request $request, DemandeRepository $demandeRepository,$id): Response
    {
        $demande = $demandeRepository->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande à été bien supprimer ');
     

        return $this->redirectToRoute('mes_demande', ['id' =>$demande->getUser()->getId()]);
    }


    /**
     * @Route("/edit-demande-User/{id}", name="edit_user_demande", methods={"GET","POST"})
     */
    public function edit(Request $request, Demande $demande,UserRepository $userRepository): Response
    {
         $user = $userRepository->find($demande->getUser()->getId());
        $form = $this->createForm(DemandeUserEditType::class, $demande);
        $form->handleRequest($request);
        //dd($user);
       if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

             $this->addFlash('success', 'Votre demande à été bien modifer ');
            return $this->redirectToRoute('mes_demande', ['id' =>$demande->getUser()->getId()]);
        }

        return $this->render('user_profil/edit.html.twig', [
            'demande' => $demande,
            'user' => $user,
            'form' => $form->createView(),
             'controller_name'=> 'Modifier demandes',
        ]);
    }

    /**
     * @Route("/Vos-offres/{id}", name="mes_offre",methods={"GET","POST"}  )
     */
    public function MesOffres(Request $request,UserRepository $userRepository ,$id, OffreRepository $offreRepository):Response
    {
        $user = $userRepository->find($id);
        $offres = $offreRepository->find($user->getId());
        
       return $this->render('user_profil/MesOffres.html.twig', [
           'user' => $user,
           'offres'=>$offres,
            'controller_name'=> 'Vos offres'
             ]);
    }

    /**
     * @Route("/Supprimer-offre-User/{id}", name="supprime_user_offre",methods={"GET","POST"}  )
     */
    public function deleteOffre(Request $request, OffreRepository $offreRepository,$id): Response
    {
        $offre= $offreRepository->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Votre offre à été bien supprimer ');
     

        return $this->redirectToRoute('mes_offre', ['id' =>$offre->getUser()->getId()]);
    }


    /**
     * @Route("/edit-offre-User/{id}", name="edit_user_offre", methods={"GET","POST"})
     */
    public function editOffre(Request $request, Offre $offre,UserRepository $userRepository): Response
    {
         $user = $userRepository->find($offre->getUser()->getId());
        $form = $this->createForm(OffreUserEditType::class, $offre);
        $form->handleRequest($request);
        //dd($user);
       if ($form->isSubmitted() && $form->isValid()) {
           // On récupère les images transmises
    $images = $form->get('images')->getData();
    
    // On boucle sur les images
    foreach($images as $image){
        // On génère un nouveau nom de fichier
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        
        // On copie le fichier dans le dossier uploads
        $image->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        $img = new Images();
        $img->setName($fichier);
        $offre->addImage($img);
    }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre offre à été bien modifer ');
            return $this->redirectToRoute('mes_offre', ['id' =>$offre->getUser()->getId()]);
        }

        return $this->render('user_profil/editOffre.html.twig', [
            'offre' => $offre,
            'user' => $user,
            'form' => $form->createView(),
             'controller_name'=> 'Modifier offre',
        ]);
    }


 /**
 * @Route("/supprime/image/{id}", name="offre_delete_image", methods={"DELETE"})
 */

public function deleteImage(Images $image, Request $request){
    $data = json_decode($request->getContent(), true);

    // On vérifie si le token est valide
    if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
        // On récupère le nom de l'image
        $nom = $image->getName();
        // On supprime le fichier
        unlink($this->getParameter('images_directory').'/'.$nom);

        // On supprime l'entrée de la base
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        // On répond en json
        return new JsonResponse(['success' => 1]);
    }else{
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }
}


}
