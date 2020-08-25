<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"groupe_write"}},
 *     collectionOperations={
 *       "get"={
 *          "path" = "admin/groupes",
 *          "normalization_context"={"groups":"groupe:read"},
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     },
 *     "getlistegroupeapprenant"={
 *          "path"="admin/groupes/apprenants",
 *          "method"="GET",
 *          "route_name"="listegroupeapprenant",
 *          "normalization_context"={"groups":"groupeapprenant:read"},
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     },
 *     "postapprenantformateur"={
 *          "path"="admin/groupes",
 *          "method"="POST",
 *          "route_name"="createapprenantformateur",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     }
 *     },
 *     itemOperations={
 *       "get"={
 *          "path"="admin/groupes/{id}",
 *          "normalization_context"={"groups":"groupe:read"},
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     },
 *     "putapprenantgroupe"={
 *          "path"="admin/groupes/{id}/apprenants",
 *          "method"="PUT",
 *          "route_name"="updateapprenantgroupe",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *
 *     },
 *     "deletegroupeapprenant"={
 *          "path"="admin/groupes/{id}/apprenants",
 *          "method"="DELETE",
 *          "route_name"="deletegroupeapprenant",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read", "groupeapprenant:read" ,"appreantgrpeprincipal:read", "promoandgroupe:read", "promoformateur:read", "groupe:read"})
     * @Groups({"briefpromogroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "groupeapprenant:read" ,"appreantgrpeprincipal:read", "groupe:read" ,"promo_write", "groupe_write", "promoandgroupe:read", "promoformateur:read"})
     * @Groups({"briefpromogroupe:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "appreantgrpeprincipal:read", "groupe:read" ,"promo_write", "groupe_write", "promoandgroupe:read", "promoformateur:read"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"promo:read", "appreantgrpeprincipal:read","groupe:read" ,"promo_write", "groupe_write", "promoandgroupe:read", "promoformateur:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "appreantgrpeprincipal:read", "groupe:read" ,"promo_write", "groupe_write", "promoandgroupe:read", "promoformateur:read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupe")
     * @Groups({"groupe:read"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     *  @Groups({"groupe:read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"appreantgrpeprincipal:read","groupeapprenant:read" ,"appreantattente:read", "promo_write", "groupe:read" ,"promoandgroupe:read"})
     * @Groups({"briefpromogroupe:read"})
     *
     *
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="groupe")
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=BriefGroupe::class, mappedBy="groupe")
     */
    private $briefGroupes;

    public function __construct()
    {
        $this->formateur = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->briefGroupes = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateur->contains($formateur)) {
            $this->formateur->removeElement($formateur);
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addGroupe($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection|BriefGroupe[]
     */
    public function getBriefGroupes(): Collection
    {
        return $this->briefGroupes;
    }

    public function addBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if (!$this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes[] = $briefGroupe;
            $briefGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if ($this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes->removeElement($briefGroupe);
            // set the owning side to null (unless already changed)
            if ($briefGroupe->getGroupe() === $this) {
                $briefGroupe->setGroupe(null);
            }
        }

        return $this;
    }
}