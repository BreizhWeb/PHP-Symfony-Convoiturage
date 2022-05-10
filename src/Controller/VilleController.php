<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Ville;

class VilleController extends AbstractController
{
     /**    
     * @Route("/insertVille/{ville}/{cp}", name="insertVille",requirements={"nom"="[a-z]{4,30}"})     
     */
    public function insert(Request $request, $ville, $cp)     { 
        $vill=new Ville(); 
        $vill->setVille($ville);
        $vill->setCodePostal($cp); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($vill); 
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
     * @Route("/deleteVille/{id}", name="deleteVille",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $VilleRepository=$em->getRepository(Ville::class); 
        //requete de selection
        $vill=$VilleRepository->find($id); 
        //suppression de l'entity
        $em->remove($vill); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 

    /**    
     * @Route("/listeVille", name="listeVille")    
     */
    public function liste(Request $request)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $VilleRepository=$em->getRepository(Ville::class); 
        //VilleRepository herite de serviceEntityRepository ayant les méthodes pour recuperer
        $listeVilles=$VilleRepository->findAll(); 
        $resultat=[];
        foreach($listeVilles as $vill){
            array_push($resultat, [$vill->getId()=>[$vill->getVille(),$vill->getCodePostal()]]);
        }
        $reponse=new JsonResponse($resultat);
        return $reponse;    
    } 
}
