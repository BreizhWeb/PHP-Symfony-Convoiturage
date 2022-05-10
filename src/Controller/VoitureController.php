<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Voiture;
use App\Entity\Marque;

class VoitureController extends AbstractController
{
    /**    
     * @Route("/insertVoiture/{idMarque}/{nbPlace}/{modele}", name="insertVoiture")     
     */
    public function insert(Request $request, $idMarque, $nbPlace, $modele)     { 
        $voiture=new Voiture(); 
        $em = $this->getDoctrine()->getManager();
        $marqueRepository = $em->getRepository(Marque::class);
        $marque = $marqueRepository->find($idMarque);
        $voiture->setMarque($marque);
        $voiture->setNbPlace($nbPlace); 
        $voiture->setModele($modele); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($voiture); 
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
     * @Route("/deleteVoiture/{id}", name="deleteVoiture",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $VoitureRepository=$em->getRepository(Voiture::class); 
        //requete de selection
        $voiture=$VoitureRepository->find($id); 
        //suppression de l'entity
        $em->remove($voiture); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 

    /**    
     * @Route("/listeVoiture", name="listeVoiture")    
     */
    public function liste(Request $request)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $VoitureRepository=$em->getRepository(Voiture::class); 
        //VoitureRepository herite de serviceEntityRepository ayant les méthodes pour recuperer
        $listeVoitures=$VoitureRepository->findAll(); 
        $resultat=[];
        foreach($listeVoitures as $voiture){
            array_push($resultat, [$voiture->getId()=>[$voiture->getMarque(),$voiture->getNbPlace(),$voiture->getModele()]]);
        }
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 
}
