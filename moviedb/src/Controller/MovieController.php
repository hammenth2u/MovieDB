<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_list")
     */
    public function index()
    {
        // On récupère la liste de tous les films
        // Des deux méthodes qu'on a vues, on garde celle qui est en une seule ligne
        // $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAllOrderedByTitle();
        // On aurait pu utiliser ceci pour le même résultat :
        // $movies = $this->getDoctrine()->getRepository(Movie::class)->findBy([],['title' => 'ASC']);
        // Mais le but de l'exercice était de manipuler le repository
        // Les méthodes du repository seront utilsi pour toutes les requêtes complexes

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/movies/{movie}/details", name="movie_single_details")
     */
    public function singleMovie(Movie $movie)
    {
        // Y'a rien à faire, merci le paramConverter qui transforme tout seul l'id dans l'url en un objet de la classe Movie

        // S02E09 - Exo 2
        // Au lieu de se fier à Doctrine pour faire des requêtes lorsqu'on affiche les noms des Person, on exécute notre propre méthode du repository
        $cast = $this->getDoctrine()->getRepository(Casting::class)->findByMovie($movie);

        // dump($cast);exit;

        return $this->render('movie/single.html.twig', [
            'movie' => $movie,
            'cast' => $cast,
        ]);
    }
}
