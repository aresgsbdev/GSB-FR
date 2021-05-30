<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class fichefraisFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Saisie");
        $manager->persist($ficheFrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
	    $fichefrais->setLibelle("En attente");
	    $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Payé");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Brouillon");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Enregistré");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Envoyé");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Consulté");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Annulé");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("En paiement");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Rapproché");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Refusée");
        $manager->persist($fichefrais);
        $manager->flush();

        $fichefrais = new FicheFrais();
        $fichefrais->setLibelle("Validée");
        $manager->persist($fichefrais);
        $manager->flush();

        $manager->flush();
    }
}
