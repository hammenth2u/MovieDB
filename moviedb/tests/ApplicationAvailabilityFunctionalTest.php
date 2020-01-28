<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * On crée une méthode qui va tester un ensemble de liens
     * Elle ne vérifie que le succès du chargement de chaque lien
     * On relie cette liste de liste de liens avec notre méthode grâcé à cette annotation
     * Pour tester chacun des url, on ajoute $url en paramètre de notre méthode
     * 
     * @dataProvider urlProviderFront
     */
    public function testIfFrontRoutesAreSuccessful($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function urlProviderFront()
    {
        yield ['/'];
        yield ['/movies/consectetur-et-ex-fugit-quis-et-culpa-perspiciatis-/details'];
        yield ['/login'];
        yield ['/register'];
    }


    /**
     * On crée une méthode qui va tester des route saccessibles en back
     * 
     * @dataProvider urlProviderBack
     */
    public function testIfBackRoutesAreSuccessful($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@t.oc',
            'PHP_AUTH_PW'   => 'admin',
        ]);
        $crawler = $client->request('GET', $url);

        // On fait la même chose que dans la méthode précédente mais
        // on envoie en plus un login et un mdp qui permettent de tester la page avec un certain utilisateur
        $statusCode = $client->getResponse()->getStatusCode();
        
        $this->assertResponseIsSuccessful();
    }

    public function urlProviderBack()
    {
        yield ['/admin/movie/'];
        yield ['/admin/casting/'];
        yield ['/secure/?action=list&entity=Films'];
        yield ['/profile'];
    }

}
