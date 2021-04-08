<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ContactType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userEncoder = $userRepository;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/contact_new", name="contact_new", methods={"GET","POST"})
     */
    public function contact_new(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(ContactType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
             // encode the plain password
             $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $messaging = new Messaging();
            $messaging->setDestinationUser($em->getRepository(User::class)->find(1));
            $messaging->setSenderUser($user);
            $messaging->setState(0);
            $messaging->setArchives(0);
            $messaging->setObject("Demande d'un accès au site.");
            $messaging->setMessage("Demande pour " . $user->getLastName()." " . $user->getFirstName());
            $messaging->setMessageDate(new DateTime());
            $messaging->setMailUser($em->getRepository(User::class)->find(1));

            $entityManager->persist($messaging);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/contact_new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
