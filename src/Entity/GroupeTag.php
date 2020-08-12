<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"grpetag_write"}},
 *     normalizationContext={"groups"={"grpetag_read"}},
 *     collectionOperations={
 *          "postgrpetag"={
 *              "method"="POST",
 *              "path"="admin/grpetags",
 *              "route_name"="creategrptag",
 *              "security"="is_granted('VIEW', object)",
 *              "security_message"="vous n'avez pas acces",
 *     },
 *     "get"={
 *          "path"="admin/grpetags",
 *          "method"="GET",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     }
 *     },
 *     itemOperations={
 *      "get"={
 *          "path"="admin/grpetags/{id}",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     },
 *      "putgrpetag"={
 *          "route_name"="updategrptag",
 *          "path"="admin/grpetags/{id}",
 *          "method"="PUT",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $isdeleted;

    /**
     * @Groups({"grpetag_write", "grpetag_read"})
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags", cascade={"persist"})
     */
    private $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }
}
