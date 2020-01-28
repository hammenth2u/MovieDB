<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Service\FileUploadManager;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Cette annotation sert à définir un préfixe pour toutes les routes déclarées plus bas
 * Ça veut dire que pour index(), la route est /movie
 * Pour new() la route est /movie/new
 * 
 * @Route("/admin/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="admin_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('admin/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_movie_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugger $slugger, FileUploadManager $fileUploadManager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Avant de persister le nouveau Movie, on lui précise son slug
            // $sluggedTitle = $slugger->slugify($movie->getTitle());
            // $movie->setSlug($sluggedTitle);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();
            
            // S04E16
            // On déplace l'image reçue dans un dossier du projet
            $imagePath = $fileUploadManager->upload($form['imageFile'], $movie->getId());
            // On attribue à la propriété image le chemin de l'image à partir de public
            $movie->setImage($imagePath);
            // On vient de remodifier le $movie, il faut reflush l'objet
            $entityManager->flush();

            return $this->redirectToRoute('admin_movie_index');
        }

        return $this->render('admin/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('admin/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_movie_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        FileUploadManager $fileUploadManager,
        Slugger $slugger,
        Movie $movie
        ): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Avant de persister le nouveau Movie, on lui précise son slug
            // $sluggedTitle = $slugger->slugify($movie->getTitle());
            // $movie->setSlug($sluggedTitle);


            // S04E16
            // On déplace l'image reçue dans un dossier du projet
            $imagePath = $fileUploadManager->upload($form['imageFile'], $movie->getId());
            // On attribue à la propriété image le chemin de l'image à partir de public
            // La méthode upload retourne soit la chemin vers l'image soit null
            // Cependant, si à l'édition si on choisit de ne pas modifier l'image.
            // et donc ne pas rechoisir une image dans le formulaire,
            // on se retrouve avec une propriété «image» de notre Movie est reset à null.
            // On utilise donc ce if qui permet d'empêcher d'écraser la valeur de Image avec null et conserver le chemin vers le fichier
            if (!($movie->getImage() !== null && $imagePath == null)) {
            // if ($movie->getImage() === null || $imagePath !== null) { // Ces deux if devraient êter équivalents
                    $movie->setImage($imagePath);
            }
            // Ce if a l'air bizarre. On y compare la valeur actuelle de image dans le Movie et la vlaeur retournée par la méthode upload()
            // Si le premier n'est null et que le second est null, on ne veut pas reset la propriété image.
            // C'est pourquoi on utilise le ! qui onverse la valeur de la comparaison

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_movie_index');
        }

        return $this->render('admin/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_movie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Movie $movie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_movie_index');
    }
}
