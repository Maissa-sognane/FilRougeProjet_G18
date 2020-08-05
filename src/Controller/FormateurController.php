<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\FormateurRepository;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formateur;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;


class FormateurController extends AbstractController
{
    /**
     * @Route(name="listerFormateur",
     *   path="api/formateurs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerFormateurController::showFormateur",
     *     "_api_resource_class"=Formateur::class,
     *     "_api_collection_operation_name"="getFormateur",
     *    }
     * )
     * @param FormateurRepository $repository
     * @param EncoderInterface $encoder
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showFormateur(FormateurRepository $repository, EncoderInterface $encoder, SerializerInterface $serializer)
    {
            $formateurs = $repository->findAll();
          //  dd($formateurs);
            $formateurs = $serializer->serialize($formateurs[0]->getPrenom(), 'json');
           // dd($formateurs);
            return $this->json($formateurs, \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * @Route(name="listerFormateurById",
     *   path="api/formateurs/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerFormateurController::showFormateurById",
     *     "_api_resource_class"=Formateur::class,
     *     "_api_collection_operation_name"="getFormateurId"
     *    }
     * )
     * @param FormateurRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showFormateurById(FormateurRepository $repository, $id, SerializerInterface $serializer)
    {
        $user = $repository->find($id);
        if($user->getProfil()->getLibelle() === "FORMATEUR"){
            $users = $serializer->serialize($user, "json");
            return new JsonResponse($users, Response::HTTP_OK,[],true);
        }else{
            return $this->json("Pas trouvé", Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @Route(name="createFormateur",
     *   path="api/formateurs",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerFormateurController::createFormateur",
     *     "_api_resource_class"=Formateur::class,
     *     "_api_collection_operation_name"="postFormateur",
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
    public function createFormateur(Request $request, ValidatorInterface $validator, SerializerInterface $serializer,
                               UserPasswordEncoderInterface $encoder, ProfilRepository $rep, EntityManagerInterface $manager){
        $user = $request->getContent();
        $userTab = $serializer->deserialize($user, Formateur::class, "json");
        $password = $userTab->getPassword();
        $userTab->setPassword($encoder->encodePassword($userTab, $password));
        $avatar = $request->files->get("avatar");
        //  $avatar = fopen($avatar->getRealPath(),"br");
        $usersJson['avatar'] = $avatar;
        if($userTab->getProfil()->getLibelle() === "FORMATEUR"){
            $manager->persist($userTab);
            $manager->flush();
            //fclose($avatar);
            return $this->json($userTab, Response::HTTP_CREATED);
        }
        else{
            return $this->json("Verifier le profil",Response::HTTP_BAD_REQUEST);
        }

    }
}


