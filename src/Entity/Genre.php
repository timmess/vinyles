<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Vinyl::class, inversedBy="genres")
     */
    private $vinyl;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="genres")
     */
    private $artists;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, inversedBy="genres")
     */
    private $albums;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->vinyl = new ArrayCollection();
        $this->artists = new ArrayCollection();
        $this->albums = new ArrayCollection();
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

    /**
     * @return Collection|Vinyl[]
     */
    public function getVinyl(): Collection
    {
        return $this->vinyl;
    }

    public function addVinyl(Vinyl $vinyl): self
    {
        if (!$this->vinyl->contains($vinyl)) {
            $this->vinyl[] = $vinyl;
        }

        return $this;
    }

    public function removeVinyl(Vinyl $vinyl): self
    {
        $this->vinyl->removeElement($vinyl);

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        $this->albums->removeElement($album);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
