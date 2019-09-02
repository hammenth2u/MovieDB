<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {  
        //importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
            
        //empile la liste d'objet à enregistrer en BDD
        foreach ($entities as $entity) {
            $em->persist($entity);
        };
            
        //enregistre
        $em->flush();
    }
        
}
