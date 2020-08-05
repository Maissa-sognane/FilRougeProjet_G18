<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ApiResource(
 *    attributes={"security"="is_granted('ROLE_ADMIN')",
 *                "security_message"="Vous n'avez pas l'acces"
 *              },
 *     collectionOperations={
 *          "getApprenant"={
 *               "method"="GET",
 *               "path"="/apprenants",
 *               "route_name"="ListerApprenant",
 *              "security"="is_granted('ROLE_FORMATEUR')",
 *              "security_message"="Vous n'avez pas l'acces"
 *     },
 *     "postApprenant"={
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="createApprenant"
 *     }
 *     },
 *     itemOperations={
 *          "get",
 *          "getApprenantId"={
 *              "method"="GET",
 *              "path"="apprenants/{id}",
 *              "route_name"="listerApprenantById",
 *              "security"="is_granted('ROLE_FORMATEUR')",
 *              "security_message"="Vous n'avez pas l'acces"
 *     },
 *     "put"={
 *          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas l'acces"
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;


    public function getId(): ?int
    {
        return $this->id;
    }
}
