<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddingDataController extends AbstractController
{
    /**
     * @Route("/add/casting", name="adding_data_casting")
     */
    public function addCasting()
    {
        // Challenge S02E09
        // Ajoutez des personnes et des films à Casting et sauvegardez-les en BDD
        // On n'a pas encore dans notre BDD de personnes ou de films, il faut aussi en créer !

        // Avant toute chose, on décide que c'est maintenant qu'on récupère l'entity manager
        // C'est optionnel, on peut le récupérer seulement au moment où on en a besoin
        // Ce qui est intéressant ici c'est qu'en le récupérant d'abord, on aura des bouts de codes mieux séparés
        $em = $this->getDoctrine()->getManager();

        // Création de deux Person
        $leonardo = new Person();
        $leonardo->setName('Leonardo DiCaprio');
        $em->persist($leonardo);

        $brad = new Person();
        $brad->setName('Brad Pitt');
        $em->persist($brad);

        // Création d'un Movie
        $ouatih = new Movie();
        $ouatih->setTitle('Once Upon a Time… in Hollywood');
        $em->persist($ouatih);

        // Création des deux Casting qui relient ces entités
        // Le casting de Leonardo
        $castingL = new Casting();
        $castingL->setRole('Rick Dalton');
        $castingL->setCreditOrder(1);
        $castingL->setPerson($leonardo);
        $castingL->setMovie($ouatih);
        $em->persist($castingL);

        // Le casting de Brad
        $castingB = new Casting();
        $castingB->setRole('Cliff Booth');
        $castingB->setCreditOrder(2);
        $castingB->setPerson($brad);
        $castingB->setMovie($ouatih);
        $em->persist($castingB);

        // On flush TOUS nos objets, les 5
        $em->flush();

        return $this->json('Once Upon a Time in Hollywood, objets were created, persisted and flushed');
    }
}
