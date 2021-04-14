<?php

namespace App\Controller;

use App\Entity\StatutLigne;
use App\Form\LigneStatusType;
use App\Repository\StatutLigneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/status")
 */
class LigneStatusController extends AbstractController
{
    /**
     * @Route("/", name="ligne_status_index", methods={"GET"})
     */
    public function index(StatutLigneRepository $statutLigneRepository): Response
    {
        return $this->render('ligne_status/index.html.twig', [
            'ligne_statuses' => $statutLigneRepository->findAll(),
            'mon_nom' => "de.sam"
        ]);
    }

    /**
     * @Route("/new", name="ligne_status_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneStatut = new StatutLigne();
        $form = $this->createForm(LigneStatusType::class, $ligneStatut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneStatut);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_status_index');
        }

        return $this->render('ligne_status/new.html.twig', [
            'ligne_status' => $ligneStatut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_status_show", methods={"GET"})
     */
    public function show(StatutLigne $statutLigne): Response
    {
        return $this->render('ligne_status/show.html.twig', [
            'ligne_status' => $statutLigne,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_status_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StatutLigne $statutLigne): Response
    {
        $form = $this->createForm(LigneStatusType::class, $statutLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_status_index');
        }

        return $this->render('ligne_status/edit.html.twig', [
            'ligne_status' => $statutLigne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_status_delete", methods={"POST"})
     */
    public function delete(Request $request, StatutLigne $statutLigne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statutLigne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($statutLigne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_status_index');
    }
}
