<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Service\Slugger;
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
     * @Route("/movies/{slug}/details", name="movie_single_details", requirements={"slug"="[a-z\-]+"})
     */
    public function singleMovie(Movie $movie)
    {
        // S04E15
        // On ne récupére plus un movie facilement avec le ParamConverter et en mettant un id dans la route
        // On cherche plutôt à récuper le Movie à partir du slug, merci le ParamConverter, ça marche tout seul
        
        
        // S02E09 - Exo 2
        // Y'a rien à faire, merci le paramConverter qui transforme tout seul l'id dans l'url en un objet de la classe Movie
        // Au lieu de se fier à Doctrine pour faire des requêtes lorsqu'on affiche les noms des Person, on exécute notre propre méthode du repository
        $cast = $this->getDoctrine()->getRepository(Casting::class)->findByMovie($movie);

        // dump($cast);exit;

        return $this->render('movie/single.html.twig', [
            'movie' => $movie,
            'cast' => $cast,
        ]);
    }

    /**
     * @Route("/movies/{id}/details", name="movie_single_details_id", requirements={"id"="\d+"})
     */
    public function singleMovieWithId(Slugger $slugger, Movie $movie)
    {
        // On a créé une autre route qui cette fois prend un id
        // Les deux routes étant similaires, pour bien faire la différence, on a été obligé de préciser que la première attend un slug constitué de lettres minuscules et de tirets présents une ou plusieurs fois
        // La seconde n'accepte que des chiffres en paramètre pour l'id

        // Notre  objectif ici c'est de faire que les anciennes routes fonctionnent toujours et qu'elles vont permetttre de créer tous les slugs au fur et à mesure des clics des visiteurs sans trop qu'on ne se fatigue

        // dump($movie);exit;

        // On crée le slug du film et on le met dans sa propriété
        $sluggedTitle = $slugger->slugify($movie->getTitle());
        $movie->setSlug($sluggedTitle);

        // On flush le film
        $entityManager = $this->getDoctrine()->getManager()->flush();

        // On redirige vers la route avec le slug !
        return $this->redirectToRoute('movie_single_details', [
            'slug' => $movie->getSlug()
        ]);
    }
}
