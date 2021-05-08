<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *              collectionOperations={},
 *              itemOperations={"get"},
 *              normalizationContext={"groups"={"module:read"}},
 *              denormalizationContext={"groups"={"module:write"}}
 * )
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("module:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("module:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Groups("module:read")
     * @Assert\Range(
     *      min = 0.5,
     *      minMessage = "La durée doit être supérieur ou égal à 0.5",
     *      )
     */
    private $nbHeures;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("module:read")
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=Seance::class, mappedBy="module", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     * @Groups("module:read")
     */
    private $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbHeures(): ?string
    {
        return $this->nbHeures;
    }

    public function setNbHeures(string $nbHeures): self
    {
        $this->nbHeures = $nbHeures;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setModule($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getModule() === $this) {
                $seance->setModule(null);
            }
        }

        return $this;
    }
}
