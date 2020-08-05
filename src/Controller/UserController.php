<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
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
     * @Route(name="listerUser",
     *   path="api/admin/users",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::showUser",
     *     "_api_resource_class"=User::class,
     *     "_api_collection_operation_name"="getUser",
     *    }
     * )
     * @param UserRepository $repository
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showUser(UserRepository $repository, SerializerInterface $serializer)
    {
        $user = $repository->findByProfil("ADMIN");
        $users = $serializer->serialize($user, "json");
        return new JsonResponse($users, Response::HTTP_OK,[],true);
    }
    /**
     * @Route(name="listerUserById",
     *   path="api/admin/users/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::showUserById",
     *     "_api_resource_class"=User::class,
     *     "_api_collection_operation_name"="getUserId"
     *    }
     * )
     * @param UserRepository $repository
     *
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showUserById(UserRepository $repository, $id, SerializerInterface $serializer)
    {
        $user = $repository->find($id);
        if($user->getProfil()->getLibelle() == "ADMIN"){
            $users = $serializer->serialize($user, "json");
            return new JsonResponse($users, Response::HTTP_OK,[],true);
        }else{
            return $this->json("Pas trouvé", Response::HTTP_NOT_FOUND);
        }

    }
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
            $userTab = $serializer->deserialize($user, User::class, "json");
            $password = $userTab->getPassword();
            $userTab->setPassword($encoder->encodePassword($userTab, $password));
            $avatar = $request->files->get("avatar");
          //  $avatar = fopen($avatar->getRealPath(),"br");
            $usersJson['avatar'] = $avatar;
        if($userTab->getProfil()->getLibelle() === "ADMIN"){
             $manager->persist($userTab);
             $manager->flush();
            //fclose($avatar);
            return $this->json($userTab,Response::HTTP_CREATED);
        }else{
            return $this->json("Verifier le profil",Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @Route(name="editUser",
     *   path="api/admin/users/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::editUser",
     *     "_api_resource_class"=User::class,
     *     "_api_collection_operation_name"="putUser",
     *    }
     * )
     * @param UserRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */

    public function editUser(UserRepository $repository, $id,SerializerInterface $serializer, Request $request,
                                UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        $users = $repository->find($id);
        dd($users);
    }
}
