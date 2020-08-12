<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PromoController extends AbstractController
{
    /**
     * @Route(name="listerefgroupe",
     *   path="api/admin/promo",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showRefFormateurGroupe",
     *     "_api_resource_class"=Promo::class,
     *     "_api_collection_operation_name"="getrefgroupe",
     *    }
     * )
     * @param PromoRepository $repository
     * @return Promo[]
     */
    public function showRefFormateurGroupe(PromoRepository $repository)
    {
        $promo = $repository->findAll();
        return $promo;
    }

    /**
     * @Route(name="listeprgeprincipal",
     *   path="api/admin/promo/principal",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showApprenantGrpePrincipal",
     *     "_api_resource_class"=Promo::class,
     *     "_api_collection_operation_name"="getgrpeprincipal",
     *    }
     * )
     * @param PromoRepository $repository
     * @return Promo[]
     */
    public function showApprenantGrpePrincipal(PromoRepository $repository)
    {
        $promo = $repository->findOneByTypeJoinedToGroup("principale");
        return $promo;
    }


    /**
     * @Route(name="listeapprenantattente",
     *   path="api/admin/promo/apprenants/attente",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showApprenantAttente",
     *     "_api_resource_class"=Promo::class,
     *     "_api_collection_operation_name"="getapprenantattente",
     *    }
     * )
     * @param PromoRepository $repository
     * @return Promo[]
     */
    public function showApprenantAttente(PromoRepository $repository)
    {
        $promo = $repository->findOneByTypeJoinedToApprenantAttente( false);
        return $promo;
    }

    /**
     * @Route(name="createpromo",
     *   path="api/admin/promo",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::createPromo",
     *     "_api_resource_class"=Promo::class,
     *     "_api_collection_operation_name"="postpromo",
     *    }
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ReferentielRepository $rep
     * @param UserRepository $repository
     * @param ApprenantRepository $repo
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function createPromo(Request $request,SerializerInterface $serializer, EntityManagerInterface $manager, ReferentielRepository $rep,
                                    UserRepository $repository, ApprenantRepository $repo, UserPasswordEncoderInterface $encoder,
                                    \Swift_Mailer $mailer)
    {
        $promo = $request->getContent();
        $promoArray = $serializer->decode($promo, "json");
        foreach ($promoArray['groupe'] as $groupe){
            $appGroupe = $serializer->encode($groupe, "json");
            $appGroupe = $serializer->deserialize($appGroupe, Groupe::class, "json");
            foreach ($groupe['apprenant'] as $apprenant) {
                $app = $repo->findByEmail($apprenant['email']);
                foreach ($app as $apprenantAJout){
                    //Generation de password par defaut
                        $length = 10;
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $charactersLength = strlen($characters);
                        $randomString = '';
                        for ($i = 0; $i < $length; $i++) {
                            $randomString .= $characters[rand(0, $charactersLength - 1)];
                        }
                    $password = $randomString;
                    $apprenantAJout->setPassword($encoder->encodePassword($apprenantAJout, $password));
                    $apprenantAJout->setIslogging(false);
                   $message = (new \Swift_Message('Ajout dans le platforme SA'))
                        ->setFrom('etudainta@gmail.com')
                        ->setTo($apprenantAJout->getEmail())
                        ->setBody(
                            'Bonjour cher(e) '.$apprenantAJout->getPrenom().' '.$apprenantAJout->getNom().' Félicitations vous aves été ajouter dans la
                            plateform Sonatel Academy. Veuillez utiliser ces informations pour vous connecter à votre promo. email: '.$apprenantAJout->getEmail().' et 
                            password: '.$password.' A Bientot !'
                        )
                    ;
                    $mailer->send($message);
                    $appGroupe->addApprenant($apprenantAJout);
                }
            }
        }
        $promo = $serializer->deserialize($promo, Promo::class, "json");
        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".", $token);
        $playload = $token[1];
        $playload = json_decode(base64_decode($playload));
        $email = $playload->username;
        $user = $repository->findOneBy(["email"=>$email]);
        $promo->setUser($user);
        $promo->addGroupe($appGroupe);
           // dd($promo);
       $manager->persist($appGroupe);
       $manager->persist($promo);
       $manager->flush();
        return $this->json($promo, Response::HTTP_CREATED);
    }


    /**
     * @Route(name="listpromoprincipalbyid",
     *   path="api/admin/promo/{id}/principal",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showPromoById",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getpromoprincipalbyid",
     *    }
     * )
     * @param PromoRepository $repo_promo
     * @param $id
     * @return Promo|null
     */

    public function showPromoById(PromoRepository $repo_promo, $id){
        $principal = $repo_promo->findOneByTypeJoinGroupPrincipal('principale', $id);
        return $principal;
    }

    /**
     * @Route(name="listpromoref",
     *   path="api/admin/promo/{id}/referentiels",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showPromoReferentiel",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getpromoref",
     *    }
     * )
     * @param PromoRepository $repo_promo
     * @param $id
     * @return Promo|null
     */

    public function showPromoReferentiel(PromoRepository $repo_promo, $id){
            $promo = $repo_promo->find($id);
            return $promo;
    }

    /**
     * @Route(name="listapprenantenattente",
     *   path="api/admin/promo/{id}/apprenants/attente",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showApprenantEnAttente",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getapprenantenattente",
     *    }
     * )
     * @param PromoRepository $repo_promo
     * @param $id
     * @return int|mixed|string
     */

    public function showApprenantEnAttente(PromoRepository $repo_promo, $id){
            $promo = $repo_promo->findOneByApprenantAttente(false, $id);
            return $promo;
    }



}
