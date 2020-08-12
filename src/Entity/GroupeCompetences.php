<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *    denormalizationContext={"groups"={"post"}},
 *    normalizationContext={"groups"={"get"}},
 *     collectionOperations={
 *      "postgrpecompetences"={
 *          "method"="POST",
 *          "path"="api/admin/grpecompetences",
 *          "route_name"="creategrpcompetences",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "get"={
 *        "path"="api/admin/grpecompetences",
 *     },
 *    "api_groupe_competences_competences_get_subresource"={
 *        "method"="GET",
 *        "path"="admin/grpecompetences/competences",
 *        "normalization_context"={"groups"={"competences"}},
 *        "security"="is_granted('VIEW', object)",
 *        "security_message"="vous n'avez pas acces",
 *     },
 *     "getgrpecompetences"={
 *         "method"="GET",
 *         "security"="is_granted('VIEW', object) or is_granted('VIEW_FORMATEUR', object)",
 *         "security_message"="vous n'avez pas acces",
 *         "path"="api/admin/grpecompetences",
 *         "route_name"="listegrpcompetences",
 *     },
 *     },
 *     itemOperations={
 *     "getgrpecompetencesbyid"={
 *          "method"="GET",
 *          "path"="admin/grpecompetences/{id}",
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_FORMATEUR', object)",
 *          "security_message"="vous n'avez pas acces",
 *          "route_name"="listegrpcompetencesById"
 *     },
 *    "api_groupe_competences_competences_get_subresource"={
 *        "method"="GET",
 *        "path"="admin/grpecompetences/{id}/competences",
 *        "normalization_context"={"groups"={"competences"}},
 *        "security"="is_granted('VIEW', object) or is_granted('VIEW_FORMATEUR', object)",
 *        "security_message"="vous n'avez pas acces"
 *     },
 *
 *     "updategrpecompetencesbyid"={
 *           "path"="api/admin/grpecompetences/{id}",
 *           "method"="PUT",
 *           "security_post_denormalize"="is_granted('EDIT', object) or is_granted('VIEW_FORMATEUR', object)",
 *           "security_post_denormalize_message"="Vous n'avez pas acces",
 *           "route_name"="updategrpcompetences"
 *     }
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 */
class GroupeCompetences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"post"})
     * @Groups({"get", "referentiel_read","referentiel_write", "ref_grpecompetence_read", "promo_referentiel:read"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @Groups({"get", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     * @Assert\NotBlank(
     *     message="Champ libelle vide"
     * )
     *
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     * @Assert\NotBlank(
     *     message="Champ description vide"
     * )
     * @Groups({"get"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Assert\NotBlank(
     *     message="Champ competences vide"
     * )
     * @Groups({"get", "competences", "post", "referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read"})
     * @ApiSubresource
     */
    private $competences;

    /**
     * @Groups({"post"})
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Assert\NotBlank(
     *     message="Champ referentiel vide"
     * )
     *
     */
    private $referentiels;

    /**
     * @Groups({"post"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="groupeCompetences")
     * @Groups({"get"})
     */
    private $user;

    /**
     * @Groups({"post"})
     * @ORM\Column(type="boolean")
     * @Groups({"get"})
     */
    private $isdeleted;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
