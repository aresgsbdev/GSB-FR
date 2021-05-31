<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Form\MessageType;
use App\Entity\Messagerie;
use App\Repository\UserRepository;
use App\Repository\MessagerieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="message_index", methods={"GET"})
     */
    public function index(MessagerieRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findByUtilisateurDestinataireMessagerie($this->getUser())
        ]);
    }

    /**
     * @Route("/new", name="message_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserRepository $userRepo): Response
    {
        $dateNow = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $message = new Messagerie();
        
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $message->setUtilisateurMessagerie($message->getUtilisateurDestinataireMessagerie());
            $message->setUtilisateurExpediteurMessagerie($this->getUser());
            $message->setDateMessageMessagerie($dateNow);
            $message->setArchive(false);
            $message->setEtat(false);
            $entityManager->persist($message);
            $entityManager->flush();

            $message->setUtilisateurMessagerie($this->getUser());
            $message->setUtilisateurExpediteurMessagerie($this->getUser());
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="message_show", methods={"GET"})
     */
    public function show(Messagerie $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Messagerie $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Messagerie $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }
}