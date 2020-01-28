<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// On a créé cette classe grâce à make:functional-test
// Les tests fonctionnels ne tesent pas des méthodes toute seules mais plutôt le résultat d'une requête
// On établit des scénario qui permettent de vérifier la présence de certaines données, de certaines balises et-ou le bon déroulement de la requête
// On peut même simuler un click ou suivre une redirection et faire des tests dans ces nouvelles pages
class MovieControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tous les films');
    }
  
    // On crée une méthode qui teste la page pour voir un seul film
    public function testSingleMovie()
    {
        // Il faut toujours instancier un client, il représente un navigateur
        // On pourrait lui préciser quel navigateur on est 
        $client = static::createClient();
        // On envoie une requête depuis ce client avec un lien écrit en dur
        // Notre but c'est de tester un lien connu, on est sûr qu'il marche
        $crawler = $client->request('GET', '/movies/consectetur-et-ex-fugit-quis-et-culpa-perspiciatis-/details');

        // On utiliser les assertions comme avec les tests unitaires
        // Il existe, depuis la versions 4.3, des assertions pour tester les pages

        // On vérifie que la requête a fonctionné
        $this->assertResponseIsSuccessful();
        // On vérifie que le DOM de la page contient un h2 dont le texte est bien «Casting»
        $this->assertSelectorTextContains('h2', 'Casting');

        // On vérifie que la page contient bien une navigation qui pointe correctement vers l'admin
        // On filtre le DOM pour récupérer l'élément a avec toutes ces classes
        // Ce filtre s'utilise comme le querySelector de JS
        // On applique la méthode link() qui permet d'obtenir un objet représentant le lien
        $link = $crawler->filter('a.btn.btn-outline-primary.my-2.my-sm-0')->link();
        
        // On se sert de l'objet $link pour simuler un clic sur le lien
        // On redéfinit $crawler en simulant un clic sur ce lien
        $crawler = $client->click($link);

        // On peut revérifier que cette nouvelle page fonctionne
        // $this->assertResponseIsSuccessful();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        // Symfony répond avec une redirection vers /login, on recré un objet $crawler à partir de là
        $crawler = $client->followRedirect();
        // On teste qu'on est bien sur la page de login
        $this->assertSelectorTextContains('h1', 'Please sign in');

    }

    public function testOldSingleMovie()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/movies/152/details');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
