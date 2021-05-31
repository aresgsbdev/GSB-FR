<?php

namespace App\DataFixtures;

use App\Entity\StatutLigne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatutLigneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Saisie");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
	    $statutligne->setLibelle("En attente");
	    $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Payé");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Brouillon");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Enregistré");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Envoyé");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Consulté");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Annulé");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("En paiement");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Rapproché");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Refusée");
        $manager->persist($statutligne);
        $manager->flush();

        $statutligne = new StatutLigne();
        $statutligne->setLibelle("Validée");
        $manager->persist($statutligne);
        $manager->flush();

        $manager->flush();
    }
}
