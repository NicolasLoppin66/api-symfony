<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Album::class)]
    private Collection $genre;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Album $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre->add($genre);
            $genre->setGenre($this);
        }

        return $this;
    }

    public function removeGenre(Album $genre): self
    {
        if ($this->genre->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getGenre() === $this) {
                $genre->setGenre(null);
            }
        }

        return $this;
    }
}
