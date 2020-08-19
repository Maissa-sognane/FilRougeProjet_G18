<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"promo_write"}},
 *     collectionOperations={
 *          "getrefgroupe"={
 *              "method"="GET",
 *              "path"="admin/promo",
 *              "route_name"="listerefgroupe",
 *              "normalization_context"={"groups":"promo:read"},
 *               "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *               "security_message"="vous n'avez pas acces"
 *     },
 *      "getgrpeprincipal"={
 *              "method"="GET",
 *              "path"="admin/promo/principal",
 *              "route_name"="listeprgeprincipal",
 *              "normalization_context"={"groups":"appreantgrpeprincipal:read"},
 *              "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *              "security_message"="vous n'avez pas acces"
 *     },
 *     "getapprenantattente"={
 *          "method"="GET",
 *          "path"="admin/promo/apprenants/attente",
 *          "route_name"="listeapprenantattente",
 *          "normalization_context"={"groups":"appreantattente:read"},
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "postpromo"={
 *          "path"="admin/promo",
 *          "method"="POST",
 *          "route_name"="createpromo",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *      },
 *     itemOperations={
 *      "get"={
 *          "path"="admin/promo/{id}",
 *          "normalization_context"={"groups":"promo:read"},
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "getpromoprincipalbyid"={
 *          "path"="admin/promo/{id}/principal",
 *          "method"="GET",
 *          "route_name"="listpromoprincipalbyid",
 *          "normalization_context"={"groups":"appreantgrpeprincipal:read"},
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *      "getpromoref"={
 *          "method"="GET",
 *          "path"="admin/promo/{id}/referentiels",
 *          "route_name"="listpromoref",
 *          "normalization_context"={"groups":"promo_referentiel:read"},
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "getapprenantenattente"={
 *          "method"="GET",
 *          "path"="admin/promo/{id}/apprenants/attente",
 *          "route_name"="listapprenantenattente",
 *          "normalization_context"={"groups":"appreantattente:read"},
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *
 *     "getpromoapprenant"={
 *          "path"="admin/promo/{id}/groupes/{groupe}/apprenants",
 *          "method"="GET",
 *          "route_name"="listepromogroupe",
 *          "normalization_context"={"groups":"promoandgroupe:read"},
 *          "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "getpromoformateur"={
 *              "path"="api/admin/promo/{id}/formateurs",
 *              "method" = "GET",
 *              "route_name"="listpromoformateur",
 *              "normalization_context"={"groups":"promoformateur:read"},
 *              "security"="is_granted('VIEW', object) or is_granted('VIEW_CM', object)",
 *               "security_message"="vous n'avez pas acces"
 *     },
 *
 *   "getupdatepromoref"={
 *          "method"="PUT",
 *          "path"="admin/promo/{id}",
 *          "route_name"="upgradepromoref",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *   "getupdatepromoapprenant"={
 *          "method"="PUT",
 *          "path"="admin/promo/{id}/apprenants",
 *          "route_name"="upgradepromoapprenant",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "getupdatepromoformateurs"={
 *          "method"="PUT",
 *          "path"="admin/promo/{id}/formateurs",
 *          "route_name"="upgradepromoformateurs",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     },
 *     "updatepromogroupe"={
 *          "method"="PUT",
 *          "path"="admin/promo/{id}/groupes/{groupe}",
 *          "route_name"="updatepromogroupe",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces"
 *     }
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read", "groupe:read" ,"appreantgrpeprincipal:read", "appreantattente:read", "promo_referentiel:read", "promoandgroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "groupe:read" ,"promo_write", "promoandgroupe:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "groupe:read" ,"promo_write", "promoandgroupe:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "groupe:read" ,"promo_write", "promoandgroupe:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "groupe:read" ,"promo_write", "promoandgroupe:read"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo_write", "promoandgroupe:read"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "groupe:read" ,"promo_write", "promoandgroupe:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo_write", "promoandgroupe:read"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:read", "promo_write"})
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"promo:read", "promo_write", "promoandgroupe:read"})
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @Groups({"promo:read", "groupe:read" ,"appreantgrpeprincipal:read", "promo_write", "promo_referentiel:read", "promoandgroupe:read", "promoformateur:read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="promos")
     *  @Groups({"promo_write"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo", cascade={"persist"})
     * @Groups({"promo:read", "appreantgrpeprincipal:read", "appreantattente:read", "promoandgroupe:read", "promoformateur:read"})
     * @ApiSubresource
     */
    private $groupe;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @Groups({"promo:read", "appreantgrpeprincipal:read", "promo_write", "promoformateur:read"})
     *
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=FileDeDiscussion::class, mappedBy="promo")
     */
    private $fileDeDiscussions;

    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->fileDeDiscussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(?\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(?\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

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
     * @return Collection|FileDeDiscussion[]
     */
    public function getFileDeDiscussions(): Collection
    {
        return $this->fileDeDiscussions;
    }

    public function addFileDeDiscussion(FileDeDiscussion $fileDeDiscussion): self
    {
        if (!$this->fileDeDiscussions->contains($fileDeDiscussion)) {
            $this->fileDeDiscussions[] = $fileDeDiscussion;
            $fileDeDiscussion->setPromo($this);
        }

        return $this;
    }

    public function removeFileDeDiscussion(FileDeDiscussion $fileDeDiscussion): self
    {
        if ($this->fileDeDiscussions->contains($fileDeDiscussion)) {
            $this->fileDeDiscussions->removeElement($fileDeDiscussion);
            // set the owning side to null (unless already changed)
            if ($fileDeDiscussion->getPromo() === $this) {
                $fileDeDiscussion->setPromo(null);
            }
        }

        return $this;
    }
}
