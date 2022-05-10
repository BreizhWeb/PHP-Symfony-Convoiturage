<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Personne;
use App\Entity\Ville;
use App\Entity\User;

class PersonneController extends AbstractController
{
    /**    
     * @Route("/insertPersonne/{idVille}/{idVoiture}/{nom}/{prenom}/{tel}/{email}", name="insertPersonne")     
     */
    public function insert(Request $request, $idVille, $idVoiture, $nom, $prenom, $tel, $email)     { 
        $pers=new Personne(); 
        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        $ville = $villeRepository->find($idVille);
        $voitureRepository = $em->getRepository(Voiture::class);
        $voiture = $voitureRepository->find($idVoiture);
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($idUser);
        $pers->setVille($ville);
        $pers->setVoiture($voiture); 
        $pers->setNom($nom); 
        $pers->setPrenom($prenom);
        $pers->setTel($tel); 
        $pers->setEmail($email);
        $pers->setUser($user);
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($pers); 
            //insertion en bdd
            $em->flush(); 
            $resultat=["ok"];         
        } else { 
            $resultat=["nok"];         
        }  
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 

    /**    
     * @Route("/deletePersonne/{id}", name="deletePersonne",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $PersonneRepository=$em->getRepository(Personne::class); 
        //requete de selection
        $pers=$PersonneRepository->find($id); 
        //suppression de l'entity
        $em->remove($pers); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 

    /**    
     * @Route("/listePersonne", name="listePersonne")    
     */
    public function liste(Request $request)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $PersonneRepository=$em->getRepository(Personne::class); 
        //PersonneRepository herite de serviceEntityRepository ayant les méthodes pour recuperer
        $listePersonnes=$PersonneRepository->findAll(); 
        $resultat=[];
        foreach($listePersonnes as $pers){
            array_push($resultat, [$pers->getId()=>[$pers->getVille(),$pers->getNom(),$pers->getPrenom(),$pers->getTel(),$pers->getEmail(),$pers->getVoiture(),$pers->getUser()]]);
        }
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 
}
