<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Genre;
use App\Entity\Casting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

//les commandes dans le terminal
// 1: composer require --dev doctrine/doctrine-fixtures-bundle pour telecharger fixture
// 2: php bin/consle make:fixture et donner le nom de la class
// pour telecharger faker : composer require fzaninotto/faker

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
               // On crée une instance de sentance en français
               $faker = Faker\Factory::create('fr_FR');
               // On passe le Manager de Doctrine à Faker !
               $populator = new Faker\ORM\Doctrine\Populator($faker, $manager);
       
               $populator->addEntity('App\Entity\Movie', 10, array(
                   'title' => function() use ($faker) { return $faker->sentence(); },
                 ));
                 $insertedEntities = $populator->execute();
               // On peut passer en 3ème paramètre le générateur de notre choix, ici un "name" cohérent pour Person
               $populator->addEntity('App\Entity\Person', 20, array(
                 'name' => function() use ($faker) { return $faker->name(); },
               ));
               $insertedEntities = $populator->execute();
               $populator->addEntity('App\Entity\Genre', 10, array(
                   'name' => function() use ($faker) { return $faker->word(); },
                 ));

                 $populator->addEntity('App\Entity\Casting', 20, array(
                     'role' => function() use ($faker) { return $faker->word(); },
                 ));
               // On demande à Faker d'éxécuter les ajouts
               $insertedEntities = $populator->execute();
               // Si besoin de manipuler les entités créées
               // dump($insertedEntities);
           
    }
}
