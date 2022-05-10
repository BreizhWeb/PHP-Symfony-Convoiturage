<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Inscription;
use App\Entity\Personne;
use App\Entity\Trajet;

class InscriptionController extends AbstractController
{
     /**    
     * @Route("/insertInscription/{idPersonne}/{idTrajet}", name="insertInscription")     
     */
    public function insert(Request $request, $idPersonne, $idTrajet)     { 
        $inscription=new Inscription(); 
        $em = $this->getDoctrine()->getManager();
        $personneRepository = $em->getRepository(Personne::class);
        $personne = $personneRepository->find($idPersonne);
        $trajetRepository = $em->getRepository(Trajet::class);
        $trajet = $trajetRepository->find($idPersonne);
        $inscription->setPers($personne);
        $inscription->setTrajet($trajet); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($inscription); 
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
     * @Route("/deleteInscription/{id}", name="deleteInscription",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $InscriptionRepository=$em->getRepository(Inscription::class); 
        //requete de selection
        $inscription=$InscriptionRepository->find($id); 
        //suppression de l'entity
        $em->remove($inscription); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 

    /**    
     * @Route("/listeInscription", name="listeInscription")    
     */
    public function liste(Request $request)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $InscriptionRepository=$em->getRepository(Inscription::class); 
        //InscriptionRepository herite de serviceEntityRepository ayant les méthodes pour recuperer
        $listeInscriptions=$InscriptionRepository->findAll(); 
        $resultat=[];
        foreach($listeInscriptions as $inscription){
            array_push($resultat, [$inscription->getId()=>[$inscription->getPers(),$inscription->getTrajet()]]);
        }
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 
}
