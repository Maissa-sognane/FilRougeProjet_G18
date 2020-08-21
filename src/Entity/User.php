<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource (
 *     normalizationContext={"groups"={"get"}},
 *     denormalizationContext={"groups":"user:write"},
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="admin/users",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas l'acces"
 *     },
 *     "postUser"={
 *              "method"="POST",
 *              "path"="admin/users",
 *              "route_name"="createUser",
 *              "deserialize"=false,
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas l'acces",
 *
 *    },
 *     },
 *     itemOperations={
 *         "get",
 *         "get"={
 *              "method"="GET",
 *              "path"="admin/users/{id}",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas l'acces"
 *     },
 *     "putUser"={
 *          "path"="admin/users/{id}",
 *          "method"="PUT",
 *          "route_name"="editUser",
 *
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas l'acces"
 *     }
 *     }
 *     )
 * * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "apprenant" = "Apprenant", "formateur" = "Formateur", "cm"="CM"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"promo:read", "formateurs_read", "promo:read", "promo_write", "get" ,"groupe_write", "apprenant:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *     message="Champ email vide"
     * )
     * @Assert\Email(
     *     message = "email invalid."
     * )
     * @Groups({"promo:read", "promo_write", "get" ,"groupe_write", "apprenant:write"})
     */
    private $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * * @Assert\NotBlank(
     *     message="Champ Password vide"
     * )
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Champ prenom vide"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le prenom est invalid"
     * )
     * @Groups({"get", "promo:read", "appreantgrpeprincipal:read", "appreantattente:read", "promoandgroupe:read", "promoformateur:read", "promo_write"})
     * @Groups({"appreantgrpeprincipal:read","groupeapprenant:read" ,"groupe:read", "apprenant:write", "briefpromogroupe:read"})
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Champ Nom vide"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le nom est invalid"
     * )
     * @Groups({"get", "groupe:read","groupeapprenant:read" ,"promo:read", "appreantgrpeprincipal:read", "appreantattente:read", "promoandgroupe:read", "promoformateur:read", "promo_write"})
     * @Groups ({"apprenant:write", "briefpromogroupe:read"})
     */
    private $Nom;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $islogging;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Assert\NotBlank(
     *     message="Champ profil vide"
     * )
     * @Groups({"user:write"})
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=GroupeCompetences::class, mappedBy="user")
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="user")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireGeneral::class, mappedBy="user")
     */
    private $commentaireGenerals;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->commentaireGenerals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getIslogging(): ?bool
    {
        return $this->islogging;
    }

    public function setIslogging(?bool $islogging): self
    {
        $this->islogging = $islogging;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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
            $groupeCompetence->setUser($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            // set the owning side to null (unless already changed)
            if ($groupeCompetence->getUser() === $this) {
                $groupeCompetence->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setUser($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getUser() === $this) {
                $promo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentaireGeneral[]
     */
    public function getCommentaireGenerals(): Collection
    {
        return $this->commentaireGenerals;
    }

    public function addCommentaireGeneral(CommentaireGeneral $commentaireGeneral): self
    {
        if (!$this->commentaireGenerals->contains($commentaireGeneral)) {
            $this->commentaireGenerals[] = $commentaireGeneral;
            $commentaireGeneral->setUser($this);
        }

        return $this;
    }

    public function removeCommentaireGeneral(CommentaireGeneral $commentaireGeneral): self
    {
        if ($this->commentaireGenerals->contains($commentaireGeneral)) {
            $this->commentaireGenerals->removeElement($commentaireGeneral);
            // set the owning side to null (unless already changed)
            if ($commentaireGeneral->getUser() === $this) {
                $commentaireGeneral->setUser(null);
            }
        }

        return $this;
    }
}
