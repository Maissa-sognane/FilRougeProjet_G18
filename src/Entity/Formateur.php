<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_ADMIN')",
 *                      "security_message"="Vous n'avez pas l'acces"
 *              },
 *     collectionOperations={
 *          "getFormateur"={
 *              "method"="GET",
 *              "path"="/formateurs",
 *              "route_name"="listerFormateur"
 *     },
 *     "postFormateur"={
 *              "method"="POST",
 *              "path"="/formateurs",
 *              "route_name"="createFormateur"
 *    }
 *     },
 *     itemOperations={
 *          "getFormateurId"={
 *              "method"="GET",
 *              "path"="formateurs/{id}",
 *              "route_name"="listerFormateurById",
 *              "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="Vous n'avez pas l'acces"
 *     },
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}


