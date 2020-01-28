<?php

namespace App\Entity;

use App\Service\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="movies")
     * @ORM\JoinTable(name="genre_movie")
     */
    private $genres;

    /**
     * Pour trier les castings par ordre de creditOrder,
     * on ajoute une annotations pour Doctrine qui indique quel champs est concerné par un ORDER BY
     * dans l'entité reliée
     * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/ordered-associations.html
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Casting", mappedBy="movie", orphanRemoval=true)
     * @ORM\OrderBy({"creditOrder" = "ASC"})
     */
    private $castings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="movie", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if ($this->genres == null) {
            $this->genres = new ArrayCollection();
        }
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres == null) {
            $this->genres = new ArrayCollection();
        }
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            $genre->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection|Casting[]
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->contains($casting)) {
            $this->castings->removeElement($casting);
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setMovie($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getMovie() === $this) {
                $team->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Cette fonction sera déclenchée lorsque les Movie sont créés ou modifiés
     * Elle crée le slug du titre et l'ajoute à la propriété
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * 
     */
    public function slugifyTitle()
    {
        // On ne peut pas injecter le Slugger dans une entité
        // On ne pourra pas non plus l'utiliser en arguement de notre méthode
        // parce que Doctrine exécute cette méthode en lui envoyant un objet représentant l'événement (Event) l'ayant délcenché
        // Cependant, on peut tout à fait instancier nous même le service, à l'ancienne

        $slugger = new Slugger();
        
        $this->slug = $slugger->slugify($this->title);

        // Le gros avantage des LifecycleCallbacks c'est qu'on peut demaner à n'importe quel contrôleur de modifier le titre d'un objet et le persister sans avoir à ajouter du code pour créer le slug. Ici, Dès que le titre sera modifié, on est sûr, à chaque fois, qu'il est mis à jour
    }
}
