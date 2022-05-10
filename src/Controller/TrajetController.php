<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Trajet;
use App\Entity\Personne;
use App\Entity\Ville;


class TrajetController extends AbstractController
{
    /**    
     * @Route("/insertTrajet/{idPersonne}/{ville_dep}/{ville_arr}/{nbKms}", name="insertTrajet")     
     */
    public function insert(Request $request, $idPersonne, $ville_dep, $ville_arr, $nbKms)     { 
        $trajet=new Trajet(); 
        $em = $this->getDoctrine()->getManager();
        $personneRepository = $em->getRepository(Personne::class);
        $personne = $personneRepository->find($idPersonne);
        $villeRepository = $em->getRepository(Ville::class);
        $ville = $villeRepository->find($ville_dep);
        $ville2Repository = $em->getRepository(Ville::class);
        $ville2 = $ville2Repository->find($ville_arr);
        $trajet->setPers($personne);
        $trajet->setVilleDep($ville); 
        $trajet->setVilleArr($ville2); 
        $trajet->setNbKms($nbKms);
        $dateTrajet = new \DateTime('now');
        $trajet->setDatetrajet($dateTrajet); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($trajet); 
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
     * @Route("/deleteTrajet/{id}", name="deleteTrajet",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $TrajetRepository=$em->getRepository(Trajet::class); 
        //requete de selection
        $trajet=$TrajetRepository->find($id); 
        //suppression de l'entity
        $em->remove($trajet); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 

    /**    
     * @Route("/listeTrajet", name="listeTrajet")    
     */
    public function liste(Request $request)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $TrajetRepository=$em->getRepository(Trajet::class); 
        //TrajetRepository herite de serviceEntityRepository ayant les méthodes pour recuperer
        $listeTrajets=$TrajetRepository->findAll(); 
        $resultat=[];
        foreach($listeTrajets as $trajet){
            array_push($resultat, [$trajet->getId()=>[$trajet->getPers(),$trajet->getVilleDep(),$trajet->getVilleArr(),$trajet->getnbKms(),$trajet->getDatetrajet()]]);
        }
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 
}
