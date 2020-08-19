<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\CM;
use App\Entity\Formateur;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Apprenant;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route(name="createUser",
     *   path="api/admin/users",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::createUser",
     *     "_api_resource_class"=User::class,
     *     "_api_collection_operation_name"="postUser",
     *    }
     * )
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $encoder
     * @param ProfilRepository $rep
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function createUser(Request $request, ValidatorInterface $validator, SerializerInterface $serializer,
                                UserPasswordEncoderInterface $encoder, ProfilRepository $rep, EntityManagerInterface $manager){
            $user = $request->getContent();
            $userJson = $serializer->decode($user, "json");
            $profil = explode("/", $userJson["profil"]);
            $profil = $rep->find($profil[2]);
            if($profil->getLibelle() === "FORMATEUR"){
                $userTab = $serializer->deserialize($user, Formateur::class, "json");
            }
            if($profil->getLibelle() === "ADMIN"){
                $userTab = $serializer->deserialize($user, User::class, "json");
            }
            if($profil->getLibelle() === "CM"){
                $userTab = $serializer->deserialize($user, CM::class, "json");
            }
            $userTab->setIslogging(false);
            $userTab->setIsDeleted(false);
            $password = $userJson["password"];
            $userTab->setPassword($encoder->encodePassword($userTab, $password));
            $avatar = $request->files->get("avatar");
          //  $avatar = fopen($avatar->getRealPath(),"br");
             $usersJson['avatar'] = $avatar;
             $manager->persist($userTab);
             $manager->flush();
            //fclose($avatar);
            return $this->json($userTab,Response::HTTP_CREATED);
    }

    /**
     * @Route(name="editUser",
     *   path="api/admin/users/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::editUser",
     *     "_api_resource_class"=User::class,
     *     "_api_item_operation_name"="putUser",
     *    }
     * )
     * @param UserRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     * @param ProfilRepository $repo_profil
     * @return JsonResponse
     */

    public function editUser(UserRepository $repository, $id,SerializerInterface $serializer, Request $request,
                                UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, ProfilRepository $repo_profil){
        $usersJson = $request->getContent();
        $usersTab = $serializer->decode($usersJson, "json");
        $users = $repository->find($id);
        $users->setPrenom($usersTab["prenom"]);
        $users->setNom($usersTab["nom"]);
        if(isset($usersTab['password'])){
            $password = $usersTab['password'];
            $users->setPassword($encoder->encodePassword($users, $password));
        }
        $manager->persist($users);
        $manager->flush();
        return $this->json($users, Response::HTTP_OK);
    }
}
