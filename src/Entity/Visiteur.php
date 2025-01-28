<?php

namespace App\Entity;
use App\Entity\Artiste;
use App\Entity\Oeuvre;


use App\Repository\VisiteurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Oeuvre::class, inversedBy: 'visiteurs')]
    #[ORM\JoinTable(name: 'visite_oeuvre')]
    private Collection $oeuvres;

    #[ORM\ManyToMany(targetEntity: Artiste::class, mappedBy: 'visiteurs')]
    private Collection $artistes;

    public function __construct() {
        $this->oeuvres = new ArrayCollection();
        $this->artistes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getOeuvres(): Collection
    {
        return $this->oeuvres;
    }

    public function addOeuvre(Oeuvre $oeuvre): static
    {
        if (!$this->oeuvres->contains($oeuvre)) {
            $this->oeuvres[] = $oeuvre;
        }

        return $this;
    }

    public function removeOeuvre(Oeuvre $oeuvre): static
    {
        $this->oeuvres->removeElement($oeuvre);

        return $this;
    }
    public function getArtiste(): ?Artiste
{
    return $this->artiste;
}

public function setArtiste(?Artiste $artiste): static
{
    $this->artiste = $artiste;

    return $this;
}
public function getNumberOfOeuvres(): int
{
    return $this->oeuvres->count();
}

public function getNumberOfArtistes(): int
{
    return $this->artistes->count();
}

}
