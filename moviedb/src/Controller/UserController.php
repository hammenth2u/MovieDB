<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        // On ajoute une vérification qui redirige l'utilisateur vers une autre page si il est connecté
        if($this->getUser() != null) {
            return $this->redirectToRoute('app_profile');
        }

        // On va traiter ici l'inscription d'un nouvel utilisateur
        // On va donc utiliser un formulaire d'inscription qu'on va envoyer à une vue
        $form = $this->createForm(UserType::class);

        // dump($form);
        // handleRequest permet de relier les informations de la requête avec le formulaire
        // Il prérempli les champs, ce qui permet de renvoyer le formulaire à la vue ave les données préremplies dedans
        // Il crée également un objet de la classe User, relié à notre formulaire UserType
        $form->handleRequest($request);

        // dump($request);exit;    

        // Lorsque la reuqête est traité par le formulaire, on teste si le formulaire a bie nété envoyé et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Si tout est bon, on a bien reçu un email et un password, on peut donc ajouter l'utilisateur
            $user = $form->getData();
            // On ajout le ROLE_USER à notre utilisateur, comme ça il y est par défaut
            $user->setRoles(['ROLE_USER']);

            // On doit encoder le mot de passe avant d'enregistrer l'utilisateur
            $plainPassword = $user->getPassword();
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            // On utilise l'entity manager pour persister et enregistrer notre objet
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Puisque tout a fonctionné, on renvoie l'utilisateur sur la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // dump($form->isValid());exit;
        // On va également traiter l'ajout de l'utilistaeur dans la base de données

        return $this->render('security/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }   

    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(Request $request)
    {
        // On a besoin d'afficher un formulaire différent de l'inscription
        // Grâce au UserType, avec l'Event, on devrait y arriver sans faire de manipulation dans le contrôleur

        // On crée l'objet Form avec l'objet de l'utilsateur
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        // C'est par ici qu'on ajouterait le code pour traiter les informations reçues par le formulaire. On n'a pas développé cette fonctionnalité pour le moment.

        // Le formulaire est déja relié à l'utilisateur, on l'envoie à la vue
        return $this->render('security/profile.html.twig', [
            'profileForm' => $form->createView(),
            'user' => $user
        ]);
    }
}
