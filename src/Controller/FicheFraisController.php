<?php

namespace App\Controller;

use DateTime;
use Exception;
use DateTimeZone;
use App\Entity\EtatFiche;
use App\Entity\FicheFrais;
use App\Entity\StatutLigne;
use App\Entity\FraisForfait;
use App\Entity\Justificatif;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FicheFraisController extends AbstractController
{
    /**
     * @Route("/fiche/frais", name="fiche_frais")
     */
    public function index(FicheFraisRepository $repo): Response
    {
        $liste = $repo->findAll();

        return $this->render('fiche_frais/index.html.twig', [
            'controller_name' => 'FicheFraisController',
            'liste_fiche_frais' => $liste,
        ]);
    }

    /**
     * @Route("/fiche/frais/new", name="fiche_frais_new")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newFicheFrais(EntityManagerInterface $manager): Response
    {
        $fraisForfaits = $manager->getRepository(FraisForfait::class)->findAll();
	$newFileName = tempnam(sys_get_temp_dir(), 'myAppNamespace');
        return $this->render('fiche_frais/new.html.twig', [
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    /**
     * @Route("/fiche/frais/store", name="store_fiche_frais", methods={"GET","POST"})
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function storeFicheFrais(EntityManagerInterface $manager, Request $request): Response
    {
	    //dd($request);
        $user = $this->getUser();
        $dateNow = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $dateEemission = DateTime::createFromFormat('Y-m-d', $request->get('dateFicheFrais'));
        $ficheFrais = new FicheFrais();
        $ficheFrais->setDateCreationFicheFrais($dateNow);
        $ficheFrais->setDateFicheFrais($dateEemission);
        $ficheFrais->setDateModificationFicheFrais($dateNow);
        $ficheFrais->setEtatFicheFrais($manager->getRepository(EtatFiche::class)->findOneByLibelle("Fiche créée, saisie en cours"));
        $ficheFrais->setNbJustificatif(0);
        $ficheFrais->setUtilisateurFicheFrais($user);
        $manager->persist($ficheFrais);
        $manager->flush();
        $dateJustificatifs = $request->request->get('dateJustificatifs');
        $dateJustificatifsFhf = $request->request->get('dateJustificatifsFhf');

        $quantites = $request->request->get('quantite');
        $fichiers = $request->files->get('files');
        foreach ($quantites as  $idFraisForfait=>$qte){
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

            if ($fichiers[$idFraisForfait] != null) {
                $filesystem = new Filesystem();

                try {
                    $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
                    $filesystem->mkdir('uploads/tmp/');
                    $filesystem->mkdir('uploads/justificatif/');
                } catch (IOExceptionInterface $exception) {
                    echo "An error occurred while creating your directory at ".$exception->getPath();
                }

                $tempFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/tmp', 'justif_');
                $finalFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/justificatif', 'justif_');
                $filesystem->remove($tempFile);
                $orinalNameFile = $fichiers[$idFraisForfait]->getClientOriginalName();

                $justificatif = new Justificatif();
                $justificatif->setChemin($finalFile);
                $justificatif->setDateCreationJustificatif($dateNow);
                $justificatif->setDateProductionJustificatif(DateTime::createFromFormat('Y-m-d', $dateJustificatifs[$idFraisForfait]));
                $justificatif->setMontant(float($qte) * $ligneff->getFraisForfait()->getMontant());
                $justificatif->setUtilisateurJustificatif($user);
                $manager->persist($justificatif);
                $manager->flush();

                $ligneff->addJustificatif($justificatif);
                $ficheFrais->setNbJustificatif($ficheFrais->getNbJustificatif() + 1);
                $manager->flush();
            }
        }

        $libelleFhf = $request->request->get('libelleFhf');
        $dateFhf = $request->request->get('dateFhf');
        $justificatifFhf = $request->files->get('justificatifFhf');
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

            if ($justificatifFhf[$key] != null) {
                $filesystem = new Filesystem();

                try {
                    $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
                    $filesystem->mkdir('uploads/tmp/');
                    $filesystem->mkdir('uploads/justificatif/');
                } catch (IOExceptionInterface $exception) {
                    echo "An error occurred while creating your directory at ".$exception->getPath();
                }

                $tempFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/tmp', 'justif_');
                $finalFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/justificatif', 'justif_');
                $filesystem->remove($tempFile);
                $orinalNameFile = $justificatifFhf[$key]->getClientOriginalName();

                $justificatif = new Justificatif();
                $justificatif->setChemin($finalFile);
                $justificatif->setDateCreationJustificatif($dateNow);
                $justificatif->setDateProductionJustificatif(DateTime::createFromFormat('Y-m-d', $dateJustificatifsFhf[$key]));
                $justificatif->setMontant($valeur);
                $justificatif->setUtilisateurJustificatif($user);
                $manager->persist($justificatif);
                $manager->flush();

                $lignefhf->addJustificatif($justificatif);
                $ficheFrais->setNbJustificatif($ficheFrais->getNbJustificatif() + 1);
                $manager->flush();
            }
        }

        return $this->redirectToRoute('fiche_frais');
    }

    /**
     * @Route("/fiche/frais/edit/{id}")
     */
    public function editFicheFrais(FicheFrais $id, EntityManagerInterface $manager) {
        $fraisForfaits = $manager->getRepository(FraisForfait::class)->findAll();

        return $this->render('fiche_frais/edit.html.twig',
            [
                "ficheFrais" => $id,
                'fraisForfaits' => $fraisForfaits,
            ]);
    }

    /**
     * @Route("/fiche/frais/update", name="update_fiche_frais", methods={"GET","POST"})
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function updateFicheFrais(EntityManagerInterface $manager, Request $request): Response
    {
        //dd($request);
        $user = $this->getUser();
        $dateNow = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $dateEemission = DateTime::createFromFormat('Y-m-d', $request->get('dateFicheFrais'));
        $ficheFrais = $manager->getRepository(FicheFrais::class)->find($request->request->get('idFicheFrais'));

        $ficheFrais->setDateFicheFrais($dateEemission);
        $ficheFrais->setDateModificationFicheFrais($dateNow);
        $manager->flush();

        $dateJustificatifs = $request->request->get('dateJustificatifs');
        $dateJustificatifsFhf = $request->request->get('dateJustificatifsFhf');

        $quantites = $request->request->get('quantite');
        $fichiers = $request->files->get('files');
        $idLignesFraisForfait = $request->request->get("idLigneFraisForfait");
        foreach ($quantites as  $idFraisForfait=>$qte){
            $ligneff=null;
            if ($idLignesFraisForfait[$idFraisForfait] != 0) {
                $ligneff = $manager->getRepository(FicheFrais::class)->find($idLignesFraisForfait[$idFraisForfait]);
            }
            if ($ligneff == null) {
                $ligneff = new LigneFraisForfait();
                $ligneff->setDateCreationLigneFraisForfait($dateNow);
                $ligneff->setUtilisateurLigneFraisForfait($user);
                $ligneff->setFraisForfait($manager->getRepository(FraisForfait::class)->find($idFraisForfait));
                $ligneff->setStatutLigneFraisForfait($manager->getRepository(StatutLigne::class)->findOneByLibelle("Saisie"));
                $ligneff->setFicheFrais($ficheFrais);
            }
            $ligneff->setQuantite($qte);
            $ligneff->setDateLigneFraisForfait($dateNow);
            $manager->persist($ligneff);
            $manager->flush();

            if ($fichiers[$idFraisForfait] != null) {
                $filesystem = new Filesystem();

                try {
                    $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
                    $filesystem->mkdir('uploads/tmp/');
                    $filesystem->mkdir('uploads/justificatif/');
                } catch (IOExceptionInterface $exception) {
                    echo "An error occurred while creating your directory at ".$exception->getPath();
                }

                $tempFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/tmp', 'justif_');
                $finalFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/justificatif', 'justif_');
                $filesystem->remove($tempFile);
                $orinalNameFile = $fichiers[$idFraisForfait]->getClientOriginalName();

                $justificatif = new Justificatif();
                $justificatif->setChemin($finalFile);
                $justificatif->setDateCreationJustificatif($dateNow);
                $justificatif->setDateProductionJustificatif(DateTime::createFromFormat('Y-m-d', $dateJustificatifs[$idFraisForfait]));
                $justificatif->setMontant(float($qte) * $ligneff->getFraisForfait()->getMontant());
                $justificatif->setUtilisateurJustificatif($user);
                $manager->persist($justificatif);
                $manager->flush();

                $ligneff->addJustificatif($justificatif);
                $ficheFrais->setNbJustificatif($ficheFrais->getNbJustificatif() + 1);
                $manager->flush();
            }
        }

        $libelleFhf = $request->request->get('libelleFhf');
        $dateFhf = $request->request->get('dateFhf');
        $justificatifFhf = $request->files->get('justificatifFhf');
        $montantFhf = $request->request->get('montantFhf');
        foreach ($montantFhf as $key=>$valeur){
            $lignefhf = null;
            if (substr($key, 0, 1) != "'") {
                $lignefhf = $manager->getRepository(LigneFraisHorsForfait::class)->find($key);
            }
            if ($lignefhf == null) {
                $lignefhf = new LigneFraisHorsForfait();
                $lignefhf->setDateCreationLigneFraisHorsForfait($dateNow);
                $lignefhf->setUtilisateurLigneFraisHorsForfait($user);
                $lignefhf->setStatutLigneFraisHorsForfait($manager->getRepository(StatutLigne::class)->findOneByLibelle("Saisie"));
                $lignefhf->setHorsClassification(false);
                $lignefhf->setFicheFrais($ficheFrais);
            }

            $lignefhf->setMontant($valeur);
            $lignefhf->setDateLigneFraisHorsForfait(DateTime::createFromFormat('Y-m-d', $dateFhf[$key]));
            $lignefhf->setLibelle($libelleFhf[$key]);
            $manager->persist($lignefhf);
            $manager->flush();

            if ($justificatifFhf[$key] != null) {
                $filesystem = new Filesystem();

                try {
                    $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
                    $filesystem->mkdir('uploads/tmp/');
                    $filesystem->mkdir('uploads/justificatif/');
                } catch (IOExceptionInterface $exception) {
                    echo "An error occurred while creating your directory at ".$exception->getPath();
                }

                $tempFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/tmp', 'justif_');
                $finalFile = $filesystem->tempnam($this->getParameter('uploads_directory') . '/justificatif', 'justif_');
                $filesystem->remove($tempFile);
                $orinalNameFile = $justificatifFhf[$key]->getClientOriginalName();

                $justificatif = new Justificatif();
                $justificatif->setChemin($finalFile);
                $justificatif->setDateCreationJustificatif($dateNow);
                $justificatif->setDateProductionJustificatif(DateTime::createFromFormat('Y-m-d', $dateJustificatifsFhf[$key]));
                $justificatif->setMontant($valeur);
                $justificatif->setUtilisateurJustificatif($user);
                $manager->persist($justificatif);
                $manager->flush();

                $lignefhf->addJustificatif($justificatif);
                $ficheFrais->setNbJustificatif($ficheFrais->getNbJustificatif() + 1);
                $manager->flush();
            }
        }

        return $this->redirectToRoute('fiche_frais');
    }

}
