<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 * @ORM\Table(name="marks")
 */
class Mark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Vehicule::class, mappedBy="mark")
     */
    private $Vehicules;

    public function __construct()
    {
        $this->Vehicules = new ArrayCollection();
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
     * @return Collection|Vehicule[]
     */
    public function getVehicules(): Collection
    {
        return $this->Vehicules;
    }

    public function addVehicule(Vehicule $Vehicule): self
    {
        if (!$this->Vehicules->contains($Vehicule)) {
            $this->Vehicules[] = $Vehicule;
            $Vehicule->setMark($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $Vehicule): self
    {
        if ($this->Vehicules->contains($Vehicule)) {
            $this->Vehicules->removeElement($Vehicule);
            // set the owning side to null (unless already changed)
            if ($Vehicule->getMark() === $this) {
                $Vehicule->setMark(null);
            }
        }

        return $this;
    }
}
