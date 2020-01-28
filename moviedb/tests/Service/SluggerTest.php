<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;


// On a créé notre classe de test avec un maker: make:unit-test
// On crée des classes de test avec la même arborescence que dans le dossier /src
class SluggerTest extends TestCase
{
    // Le nom des méthodes qui seront exécutées par PHPUnit commencent par «test»
    // Ici, slugify est le nom de la méthode que nous allons tester
    public function testSlugify()
    {
        // On instancie la classe Slugger
        $slugger = new Slugger();

        // On exécute la méthode slugify ici
        $slug = $slugger->slugify('Men In Black IV');
        
        // On vérifie le résultat pour un titre particulier
        $this->assertEquals('men-in-black-iv', $slug);

        // On exécute la méthode slugify ici mais avec un autre titre, on va tester un cas particulier
        $slug = $slugger->slugify('');
        
        // On vérifie le résultat pour une string vide
        $this->assertEquals('', $slug);

        $slug = $slugger->slugify(null);

        $this->assertEquals('', $slug);

        $slug = $slugger->slugify(123456789);

        $this->assertEquals('123456789', $slug);

        $slug = $slugger->slugify('12345 67 89');

        $this->assertEquals('12345-67-89', $slug);
    }
}
