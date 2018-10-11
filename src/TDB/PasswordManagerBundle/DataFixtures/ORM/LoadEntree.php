<?php

namespace TDB\PasswordManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TDB\PasswordManagerBundle\Entity\Entree;
use TDB\PasswordManagerBundle\Entity\Service;
use TDB\PasswordManagerBundle\Entity\Acces;

class LoadEntree implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $entree_noms = array(
      'E-SYSTEMES',
      'NORAUTO',
      'AMAZONE',
      'Auchan',
      'Decathlon',
      'MAMMOUTH',
      'Shopi',
      'Casino',
      'APPLE',
      'DLM',
      'Renault',
      'BricoDepot',

    );
    $services_nom = array(
      'BDD',
      'Phpmyadmin',
      'FTP',
    );
    $access = array(
      'login' => '',
      'password' => '',
    );


    foreach ($entree_noms as $entree_nom) {
        // On crée l'entrée
        $entree = new Entree();
        $entree->setEntreeNom($entree_nom);

        foreach($services_nom as $service_nom){

            $service = new Service();
            $service->setServiceNom($service_nom);

            foreach($access as $clef => $valeur){
                $chaine  ='abcdefghijklmnopqrstuvwxyz0123456789';
                $melange = str_shuffle($chaine);
                $pass = substr($melange,0,5);

                $acces = new Acces();
                $acces->setClef($clef);
                $acces->setValeur($entree_nom.$pass);
                // On lie l'accès au service
                $service->addAcces($acces);
                // Puis on lie le service à l'accès
                $acces->setService($service);
                $manager->persist($acces);
            }
        $entree->addService($service);
        $service->setEntree($entree);
        $manager->persist($service);
        }
    $manager->persist($entree);
    }
    $manager->flush();

    }
}
