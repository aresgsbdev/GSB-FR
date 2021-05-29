<?php

namespace App\Controller;

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
     * @Route("/api/user/list/{id}", name="api_user_list")
     */
    public function index(UserRepository $userRepository, User $user): Response
    {

    //si nous sommes connecté en tant qu'admin et superadmin, nous pouvons tout voir, sinon, nous ne voyons rien.
        if (in_array("ROLE_ADMIN", $user->getRoles()) || in_array("ROLE_SUPER_ADMIN", $user->getRoles())){

        $data = $userRepository->findAll();
    } else {
        $data = ["user"=>false];
    }

    //retour en json de notre public function.
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($serializer->serialize($data, 'json'));
    }

    /**
     * @Route("/api/user/data/{id}", name="api_user_data")
     */
    public function apiUserData(User $user): Response
    {
    
    //retour en json de notre public function.
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializer->serialize($user, 'json');
   
    return JsonResponse::fromJsonString($serializer->serialize($user, 'json'));
    }
}