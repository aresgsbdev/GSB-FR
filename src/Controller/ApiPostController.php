<?php

namespace App\Controller;

 use App\Entity\User;
 use App\Entity\FicheFrais;
 use App\Repository\PostRepository;
 use App\Repository\UserRepository;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\Security\Core\User\UserInterface;
 use Symfony\Component\Security\Core\User\UserProviderInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * Permet de tester la connexion sur la page de login del'API Java.
     *
     * @Route("/api/connexion", name="api_connexion", methods={"POST","GET"})
     * @return void
     */
    public function apiConnexion(UserRepository $userRepo,Request $request,UserPasswordEncoderInterface $encoder)
    {
        $connexion = json_decode($request->getContent());

        $user = $userRepo->findOneByEmail($connexion->email);
        // $encodedPassword=
        // $encoder->encodePassword(
        // $user,
        // $connexion->password
        // );
            
        $isPasswordValid = $encoder->isPasswordValid($user, $connexion->password);
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        if (!$isPasswordValid) {
            return JsonResponse::fromJsonString($serializer->serialize(['user'=> false], 'json'));
        } else {

            $utilisateur["nom"]=$user->getNom();
            $utilisateur["prenom"]=$user->getPrenom();
            $utilisateur["id"]=$user->getId();

            return JsonResponse::fromJsonString($serializer->serialize($utilisateur, 'json'));
        }
    }

    /**
     * créer une fiche de frais et ensuite une lignefraisforfait et lignefraishorsforfait
     *
     * @Route("/api/fichefrais/new/", name="api_new_fichefrais",methods={"PUT","POST"})
     * @return JSON
     */

    //création fichefrais
    public function apiNewfichefrais(Request $request,FicheFrais $fichefrais)
    {
        dd($request);
        $entityManager = $this->getDoctrine()->getManager();
        $fichefrais = new fichefrais();
        $dateNow = new DateTime('NOW');
        $fichefrais->setDateFicheFrais($dateNow);
        $user = $this->getUser();
        $request->request($dateEemission);

        $ficheFrais->setDateFicheFrais($dateEemission);
        $ficheFrais->setDateModificationFicheFrais($dateNow);
        $ficheFrais->setEtatFicheFrais($manager->getRepository(EtatFiche::class)->findOneByLibelle("Fiche créée, saisie en cours"));
        $ficheFrais->setUtilisateurFicheFrais($user);
        //$manager->persist($ficheFrais);
        //$manager->flush();
        $entityManager->persist($fichefrais);
        $entityManager->flush();
    
        //création lignefraisforfait
        $quantites = $request->request->get('quantite');
        $fichiers = $request->files->get('files');
        foreach ($quantites as $idFraisForfait=>$qte){
            $ligneff = new LigneFraisForfait();
            $ligneff->setDateCreationLigneFraisForfait($dateNow);
            $ligneff->setQuantite($qte);
            $ligneff->setDateLigneFraisForfait($dateNow);
            $ligneff->setUtilisateurLigneFraisForfait($user);
            $ligneff->setFraisForfait($manager->getRepository(FraisForfait::class)->find($idFraisForfait));
            $ligneff->setStatutLigneFraisForfait($manager->getRepository(StatutLigne::class)->findOneByLibelle("Saisie"));
            $ligneff->setFicheFrais($ficheFrais);
            $manager->persist($ligneff);
            $manager->flush();
        }
    
        //création lignefraishorsforfait
        $libelleFhf = $request->request->get('libelleFhf');
        $dateFhf = $request->request->get('dateFhf');
        $montantFhf = $request->request->get('montantFhf');
        foreach ($montantFhf as $key=>$valeur){
            $lignefhf = new LigneFraisHorsForfait();
            $lignefhf->setDateCreationLigneFraisHorsForfait($dateNow);
            $lignefhf->setMontant($valeur);
            $lignefhf->setDateLigneFraisHorsForfait(DateTime::createFromFormat('Y-m-d', $dateFhf[$key]));
            $lignefhf->setUtilisateurLigneFraisHorsForfait($user);
            $lignefhf->setStatutLigneFraisHorsForfait($manager->getRepository(StatutLigne::class)->findOneByLibelle("Saisie"));
            $lignefhf->setHorsClassification(false);
            $lignefhf->setLibelle($libelleFhf[$key]);
            $lignefhf->setFicheFrais($ficheFrais);
            $manager->persist($lignefhf);
            $manager->flush();
        }
        return JsonResponse::fromJsonString($serializer->serialize($fichefrais, 'json'));
    }
}