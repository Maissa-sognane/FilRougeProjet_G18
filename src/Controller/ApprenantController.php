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
     *     "_api_resource_class"=Apprenant::class,
     *     "_api_collection_operation_name"="getApprenant",
     *    }
     * )
     * @param ApprenantRepository $repository
     * @param SerializerInterface $serializer
     * @return Apprenant[]
     */
    public function showApprenant(ApprenantRepository $repository, SerializerInterface $serializer)
    {
        $apprenants = $repository->findAll();
        return $apprenants;
    }

    /**
     * @Route(name="createApprenant",
     *   path="api/apprenants/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerApprenantController::ChangeEtatApprenant",
     *     "_api_resource_class"=Apprenant::class,
     *     "_api_item_operation_name"="postApprenant",
     *    }
     * )
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $encoder
     * @param ProfilRepository $rep
     * @param EntityManagerInterface $manager
     * @param ApprenantRepository $repository
     * @param $id
     * @return Apprenant|JsonResponse|null
     */
    public function ChangeEtatApprenant(Request $request, ValidatorInterface $validator, SerializerInterface $serializer,
                                    UserPasswordEncoderInterface $encoder, ProfilRepository $rep, EntityManagerInterface $manager,
                                    ApprenantRepository $repository, $id){
        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".", $token);
        $playload = $token[1];
        $playload = json_decode(base64_decode($playload));
        $email = $playload->username;
        $user = $repository->findOneBy(["email"=>$email]);
        $apprenant = $repository->find($id);
        if($apprenant->getEmail() === $user->getEmail()){
            if($apprenant->getIslogging() === false){
                $apprenant->setIslogging(true);
            }
        }else{
            return $this->json("Non Acces", Response::HTTP_BAD_REQUEST);
        }
        $app = $request->getContent();
        $app = $serializer->decode($app, "json");
        $password = $app['password'];
        $apprenant->setPassword($encoder->encodePassword($apprenant, $password));
        $apprenant->setPrenom($app['prenom']);
        $apprenant->setNom($app['nom']);
        $manager->persist($apprenant);
        $manager->flush();
        return $this->json($apprenant, Response::HTTP_OK);
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
     * @return Apprenant
     */
    /*
    public function showApprenantById(ApprenantRepository $repository, $id, SerializerInterface $serializer)
    {
        $user = $repository->find($id);
        if($user->getProfil()->getLibelle() === "APPRENANT"){
           // $users = $serializer->serialize($user, "json");
            return $user;
        }

    }
    */


    /**
     * @Route(name="createApprenant",
     *   path="api/apprenants",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerUserController::createApprenant",
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
        $user = $request->request->all();
        $profil = explode("/", $user["profil"]);
        $profil = $rep->find($profil[2]);
        $avatar = $request->files->get("avatar");
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user['avatar'] = $avatar;
        if($profil->getLibelle() === "APPRENANT"){
            $userTab = $serializer->denormalize($user, Apprenant::class);
            $userTab->setIslogging(false);
            $userTab->setIsDeleted(false);
            $password = $user["password"];
            $userTab->setPassword($encoder->encodePassword($userTab, $password));

            $manager->persist($userTab);
            $manager->flush();
            fclose($avatar);
            return $this->json($userTab,Response::HTTP_CREATED);
        }
        else{
            return $this->json("Profil Non autorisé", Response::HTTP_BAD_REQUEST);
        }

    }
}
