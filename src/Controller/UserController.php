<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Security\TokenAuthenticator;
use App\Entity\User;

class UserController extends AbstractController
{
    /**    
     * @Route("/register/{username}/{password}/{prepassword}/{tel}/{email}", name="registerUser")     
     */
    public function register(Request $request, $username, $password)     { 
        $user=new User(); 
        $em = $this->getDoctrine()->getManager();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);        
        $user->setUsername($username); 
        $user->setRoles('ROLE_USER'); 
        $user->setPrepassword($passwordHash);
        $user->setApiToken($token);
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($user); 
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
     * @Route("/login/{username}/{password}", name="loginUser")       
     */
    public function login(Request $request, $username, $password)     { 
        //récupération du Manager
        $em=$this->getDoctrine()->getManager(); 
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findBy(['username' => $login]);
        $passwordBDD = $user[0]->getPassword();
        
        //Verification du mot de passe
        $passwordVerify = password_verify($passwordBDD, $password);
        if ($passwordVerify) {
            $tokken = $user[0]->getApiToken();
            $resultat=[$tokken]; 
        }else{
            $resultat=["nok"];
        }
        $reponse=new JsonResponse($resultat); 
        return $reponse; 
    }
}
