<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Genre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="genres")
     */
    private $movies;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->movies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if ($this->movies == null) {
            $this->movies = new ArrayCollection();
        }
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies == null) {
            $this->movies = new ArrayCollection();
        }
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
        }

        return $this;
    }

    /**
     * Avec cette anotation on automatise que cette méthode de l'entité doit être exécutée au moment d'un persist
     * Ça nous permet d'attribuer des données par défaut à cartins champs qui ne seraient dans le formulaire
     * Exemple ici avec updatedAt
     * @ORM\PreUpdate
     * 
     * Documentation :
     * Attention il faut l'annotation HasLifecycleCallbacks au-dessus de la classe
     *  - https://symfony.com/doc/current/doctrine/lifecycle_callbacks.html
     *  - https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/events.html
     */
    public function changeUpdatedAtOnPersist()
    {
        $this->updatedAt = new \DateTime();
    }
}
