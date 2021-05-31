<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Repository\FicheFraisRepository;

 use App\Entity\User;
 use App\Repository\PostRepository;
 use App\Repository\UserRepository;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/user/list", name="api_user_list")
     */
    public function index(UserRepository $userRepository): Response
    {
        $data = $userRepository->findAll();
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($serializer->serialize($data, 'json'));
    }

    /**
     * @Route("/api/fiche/frais/list/{id}", name="api_fiche_frais_list")
     */
    public function apiListFicheFrais(FicheFraisRepository $ficheFraisRepository,User $user)
    {
        if (in_array("ROLE_ADMIN", $user->getRoles()) || in_array("ROLE_SUPER_ADMIN", $user->getRoles()) || in_array("ROLE_COMPTABLE", $user->getRoles()) ) {

            $data = $ficheFraisRepository->findAll();
        } else  {
            $data = $ficheFraisRepository->findByUtilisateurFicheFrais($user);
        }
        
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($serializer->serialize($data, 'json'));
    }

    /**
     * @Route("/api/fiche/frais/detail/{id}", name="api_fiche_frais_detail")
     */
    public function apiDetailFicheFrais(FicheFrais $ficheFrais)
    {
        
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializer->serialize($ficheFrais, 'json');

        return JsonResponse::fromJsonString($serializer->serialize($ficheFrais, 'json'));
    }

    


}

// Mon code si dessous

// namespace App\Controller;

// use App\Entity\User;
// use App\Repository\PostRepository;
// use App\Repository\UserRepository;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class ApiPostController extends AbstractController
// {
//      public $serializer;

//     /**
//      * Permet de récupérer la liste des users.
//      * @Route("/api/user/list", name="api_user_list", methods={"GET", "POST"})
//      */

//     public function apiGetUser(UserRepository $userRepository): Response
//     {
//         $userRepository = new Response();
//         return $this->json($userRepository);
//         $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
//     }
// }