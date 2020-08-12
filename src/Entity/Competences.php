<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *       denormalizationContext={"groups"={"post", "competence_write"}},
 *       normalizationContext={"groups"={"competence_read"}},
 *     collectionOperations={
 *          "get"={
 *             "path"="admin/competences",
 *             "security"="is_granted('VIEW', object) or is_granted('VIEW_FORMATEUR', object)",
 *             "security_message"="vous n'avez pas acces",
 *     },
 *     "postcompetences"={
 *          "path"="admin/competences",
 *          "method"="POST",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *          "route_name"="createcompetences"
 *     }
 *     },
 *     itemOperations={
 *          "getcompetencesbyid"={
 *              "path"="admin/competences/{id}",
 *              "method"="GET",
 *              "security"="is_granted('VIEW', object) or is_granted('VIEW_FORMATEUR', object)",
 *              "security_message"="vous n'avez pas acces",
 *              "route_name"="listecompetencesById"
 *     },
 *    "updatecompetencesid"={
 *          "method"="PUT",
 *          "route_name"="updatecompetences",
 *          "path"="admin/competences/{id}"
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 */
class Competences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"post", "competence_write", "get","referentiel_read"})
     * @Groups({"competence_read","referentiel_id", "ref_grpecompetence_read", "promo_referentiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "competence_write", "get"})
     * * @Assert\NotBlank(
     *     message="Champ libelle vide"
     * )
     * @Groups({"competence_read", "competences", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"post", "competence_read", "competence_write", "get", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     */
    private $isdeleted;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "competences", "competence_read", "competence_write", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     * * @Assert\NotBlank(
     *     message="Champ descriptif vide"
     * )
     */
    private $descriptif;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competences" , cascade={"persist"})
     * @Groups({"competence_read", "post", "competence_write", "referentiel_read", "get", "ref_grpecompetence_read"})
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="competences")
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->niveau = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIsdeleted(): ?bool
    {
        return $this->isdeleted;
    }

    public function setIsdeleted(bool $isdeleted): self
    {
        $this->isdeleted = $isdeleted;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
            $niveau->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetences() === $this) {
                $niveau->setCompetences(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }
}
