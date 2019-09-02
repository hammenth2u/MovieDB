# Challenges ajout de données avec DoctrineFixturesBundle

> Les jeux de données de "test" sont cruciaux pour démarrer un projet. Ils permettent:
>    - Pour un projet cloné, d'initialiser un jeu de données initiales.
>    - La possibilité à tout moment de réinitialiser les données de développement.
>    - Et surtout, ne plus s'embêter à créer les données à la main ou à faire transiter des fichiers .sql de données...

## Objectifs

- **Générer des données dans toutes les tables** via `DoctrineFixturesBundle`.
- Vérifier que les pages `list` et `show` s'affichent correctement.

**Vous avez 3 challenges disponibles au choix**, choisissez celui qui vous parle le plus.

### Conseils

- Commencez "petit" et avancez par étapes.
- Ne pas hésiter à exécuter `php bin/console doctrine:fixtures:load` à outrance.
- Have fun :)

## Option 1. Tout à la mano, pour comprendre ce qu'on fait (méthode fournie)

- A partir du début de code fourni [et/ou de cette portion de doc](https://symfony.com/doc/current/doctrine.html#dummy-data-fixtures), créez des données dans toutes les tables.
- Utilisez des boucles pour créer ces données. Sans relations.
  - => Cette partie ne devrait pas poser de soucis.
- Associez les entités liées entre elles, si possible sans doublons.
  - => Pensez à stocker vos entités dans des listes pour pouvoir les retrouver ensuite (usage malin du random, afin de piocher dans ces listes).
- Idéalement, créer des classes et des méthodes vous renvoyant des données préconçues (un film au hasard parmi une liste de films, etc.).
  - Une classe contenant des tableaux de données par entité, et des méthodes d'accès pour récupérer ces entités au hasard.

## Option 2. Avec Faker : données factices et relations automatiques

### Instructions

- Utiliser Faker pour **générer des données réalistes** ou un minimum cohérentes.
- [Fiche récap' usage DoctrineFixtures AVEC Faker](https://github.com/O-clock-Alumnis/fiches-recap/blob/master/symfony/themes/fixturesbundle-faker.md).
- **Créer un ou plusieurs `Faker Provider`** afin de fournir à Faker un generator, par ex. pour `Movies`, `Person` ou `Genres`.

### Bonus Faker debug ManyToMany

- **Le "Populator" sur la relation ManyToMany étant _buggué_, trouver un moyen d'y remédier (à la mano) grâce aux `insertedPKs` fournies par Faker** lors de l'ajout des données.
-  Associer 3 `Genre` au hasard sur un `Movie`.
- Des doublons peuvent exister (même Genre associé plusieurs fois au même film) car on associe 3 `Genre` par `Movie`.
  - Trouver un moyen pour qu'il n'y ait pas de `Genre` en doublon ;)

<details>
  <summary>Spoiler process</summary>

  - On récupere les genres et les movies générés en retour de `$populator->execute()`.
  - On parcourt les movies et pour chaque movie on associe des genres.
  > On rencontre une erreur lors de l'ajout d'un Genre sur un Movie. Regarder d'où provient l'erreur et dumper pour comprendre ce qu'il se passe. Trouver un moyen de fixer / de contourner cette erreur.
  - On persist.
  - On flush.

</details> 


## Option 3. AliceBundle : le tout-en-un

Plusieurs bundles existent, souvent basé sur Faker, proposent une approche plus basée sur la configuration des fixtures que leur écriture en code. C'est le cas d'`AliceBundle`.

- Adapter/refaire partiellement ou totalement les fixtures existantes en utilisant le bundle `nelmio/alice` en se basant sur cette [Fiche Récap'](https://github.com/O-clock-Alumni/fiches-recap/blob/master/symfony/themes/fixtures-advanced-alice.md).
