<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Formateur;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Apprenant;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApprenantController extends AbstractController
{
    /**
     * @Route(name="ListerApprenant",
     *   path="api/apprenants",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerApprenantController::showApprenant",
     *     "_api_resource_class"=User::class,
     *     "_api_collection_operation_name"="getApprenant",
     *    }
     * )
     * @param UserRepository $repository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function showApprenant(UserRepository $repository, SerializerInterface $serializer)
    {
        $apprenants = $repository->findByProfil("APPRENANT");
        $apprenants = $serializer->serialize($apprenants, "json");
        return new JsonResponse($apprenants, Response::HTTP_OK,[],true);
    }
    /**
     * @Route(name="createApprenant",
     *   path="api/apprenants",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerApprenantController::createApprenant",
     *     "_api_resource_class"=Apprenant::class,
     *     "_api_collection_operation_name"="postApprenant",
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
    public function createApprenant(Request $request, ValidatorInterface $validator, SerializerInterface $serializer,
                                    UserPasswordEncoderInterface $encoder, ProfilRepository $rep, EntityManagerInterface $manager){
        $user = $request->getContent();
        $userTab = $serializer->deserialize($user, Apprenant::class, "json");
        $password = $userTab->getPassword();
        $userTab->setPassword($encoder->encodePassword($userTab, $password));
        $avatar = $request->files->get("avatar");
        //  $avatar = fopen($avatar->getRealPath(),"br");
        $usersJson['avatar'] = $avatar;

        if($userTab->getProfil()->getLibelle() === "APPRENANT"){
            $manager->persist($userTab);
            $manager->flush();
            //fclose($avatar);
            return $this->json($userTab, Response::HTTP_CREATED);
        }
        else{
            return $this->json("Verifier le profil",Response::HTTP_BAD_REQUEST);
        }

    }


    /**
     * @Route(name="listerApprenantById",
     *   path="api/apprenants/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerApprenantController::showApprenantById",
     *     "_api_resource_class"=Apprenant::class,
     *     "_api_collection_operation_name"="getApprenantId"
     *    }
     * )
     * @param ApprenantRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showApprenantById(ApprenantRepository $repository, $id, SerializerInterface $serializer)
    {
        $user = $repository->find($id);
        if($user->getProfil()->getLibelle() === "APPRENANT"){
            $users = $serializer->serialize($user, "json");
            return new JsonResponse($users, Response::HTTP_OK,[],true);
        }else{
            return $this->json("Pas trouvé", Response::HTTP_BAD_REQUEST);
        }

    }
}
