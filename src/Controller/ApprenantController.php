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
