<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @throws Exception
     */
    public function createPromo(Request $request,SerializerInterface $serializer, EntityManagerInterface $manager, ReferentielRepository $rep,
                                    UserRepository $repository, ApprenantRepository $repo, UserPasswordEncoderInterface $encoder,
                                    \Swift_Mailer $mailer)
    {

        $promo = $request->getContent();
        $promoArray = $serializer->decode($promo, "json");
        //dd($promoArray['groupe'][0]['apprenant']);
        foreach ($promoArray['groupe'] as $groupe){
            $appGroupe = $serializer->encode($groupe, "json");
            $appGroupe = $serializer->deserialize($appGroupe, Groupe::class, "json");
            foreach ($groupe['apprenant'] as $apprenant) {
              //  dd($apprenant);
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
        $date = new \DateTime('@'.strtotime('now'));
        $appGroupe->setDateCreation($date);
        $promo->setUser($user);
        $promo->addGroupe($appGroupe);
            dd($promo->getReferentiel()->getGroupeCompetences()->getValues()[0]->getCompetences()->getValues());
      // $manager->persist($appGroupe);
     //  $manager->persist($promo);
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

    /**
     * @Route(name="listepromogroupe",
     *   path="api/admin/promo/{id}/groupes/{groupe}/apprenants",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showPromoApprenant",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getpromoapprenant",
     *    }
     * )
     * @param PromoRepository $repo_promo
     * @param $id
     * @param $groupe
     * @return int|mixed|string
     */

    public function showPromoApprenant(PromoRepository $repo_promo, $id, $groupe){
            $promo = $repo_promo->findOnePromoGroupe($groupe, $id);
            return $promo;

    }

    /**
     * @Route(name="listpromoformateur",
     *   path="api/admin/promo/{id}/formateurs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::showPromoFormateur",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getpromoformateur",
     *    }
     * )
     * @param Request $request
     * @param $id
     * @param PromoRepository $repo_promo
     * @return Promo|null
     */

    public function showPromoFormateur(Request $request, $id, PromoRepository $repo_promo){
        $promo = $repo_promo->find($id);
        return $promo;
    }

    /**
     * @Route(name="upgradepromoref",
     *   path="api/admin/promo/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::updatepromoref",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getupdatepromoref",
     *    }
     * )
     * @param PromoRepository $repo_promo
     * @param $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */

    public function updatepromoref(PromoRepository $repo_promo, $id, Request $request , SerializerInterface $serializer, EntityManagerInterface $manager){
            $promotion = $request->getContent();
            $promotion = $serializer->decode($promotion, "json");
            $promo = $repo_promo->find($id);
            $promo->setDescription($promotion['description']);
            $promo->setDateDebut($promotion['dateDebut']);
            $promo->setFabrique($promotion['fabrique']);
            $promo->setDateFinReelle($promotion['dateFinReelle']);
            $promo->setLieu($promotion['lieu']);
            if($promo->getReferentiel()->getId() === $promotion['referentiel']['id']){
                $manager->persist($promo);
                $manager->flush();
            }
            else{
                $promo->setReferentiel($promotion['referentiel']['id']);
                $manager->persist($promo);
                $manager->flush();
            }
            return $this->json($promo, Response::HTTP_OK);
    }


    /**
     * @Route(name="upgradepromoapprenant",
     *   path="api/admin/promo/{id}/apprenants",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::updatePromoApprenant",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getupdatepromoapprenant",
     *    }
     * )
     * @param Request $request
     * @param PromoRepository $repo_promo
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param $id
     * @param ApprenantRepository $repo_apprenant
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return JsonResponse
     */

    public function updatePromoApprenant(Request $request, PromoRepository $repo_promo, EntityManagerInterface $manager, SerializerInterface $serializer, $id,
                                            ApprenantRepository $repo_apprenant, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer){
            $promo = $request->getContent();
            $promo = $serializer->decode($promo, "json");
            $promotion = $repo_promo->findOneByTypeJoinGroupPrincipal('principale', $id);
           // dd($promotion[0]->getGroupe()[0]->getApprenant()[0]);
            foreach ($promotion as $pro){
                foreach ($pro->getGroupe() as $groupe){
                   foreach ($groupe->getApprenant() as $apprenant){
                       foreach ($promo['groupe'] as $app){
                           if(count($app) === 1){
                               $apprenantAJout = $repo_apprenant->findByEmail($app['email']);
                               $apprenantAJout = $apprenantAJout[0];
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
                               $groupe->addApprenant($apprenantAJout);
                           }
                           else{
                               $apprenantAJout = $repo_apprenant->find($app['id']);
                               $groupe->removeApprenant($apprenantAJout);
                           }
                       }
                   }
                }
            }

       $manager->persist($groupe);
       $manager->flush();
        return $this->json($promo, Response::HTTP_CREATED);
    }

    /**
     * @Route(name="upgradepromoformateurs",
     *   path="api/admin/promo/{id}/formateurs",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::updatePromoFormateur",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="getupdatepromoformateurs",
     *    }
     * )
     * @param Request $request
     * @param $id
     * @param SerializerInterface $serializer
     * @param PromoRepository $repository_promo
     * @param EntityManagerInterface $manager
     * @param FormateurRepository $repository_form
     * @return JsonResponse
     */

    public function updatePromoFormateur(Request $request, $id, SerializerInterface $serializer, PromoRepository $repository_promo, EntityManagerInterface $manager,
                                            FormateurRepository $repository_form){
        $formateur = $request->getContent();
        $formateur = $serializer->decode($formateur, "json");
        $promo = $repository_promo->find($id);
        foreach ($formateur['formateur'] as $form){
            if(count($form) === 1){
                $formUser = $repository_form->find($form['id']);
                $promo->addFormateur($formUser);
            }
            else{
                $formUser = $repository_form->find($form['id']);
                $promo->removeFormateur($formUser);
            }

        }
        $manager->persist($promo);
        $manager->flush();
        return $this->json($promo, Response::HTTP_OK);
    }

    /**
     * @Route(name="updatepromogroupe",
     *   path="api/admin/promo/{id}/groupes/{groupe}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerPromoController::updatePromoGroupe",
     *     "_api_resource_class"=Promo::class,
     *     "_api_item_operation_name"="updatepromogroupe",
     *    }
     * )
     * @param Request $request
     * @param PromoRepository $repository_promo
     * @param GroupeRepository $repository_groupe
     * @param $id
     * @param $groupe
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */

    public function updatePromoGroupe(Request $request, PromoRepository $repository_promo, GroupeRepository $repository_groupe, $id, $groupe,
                                        SerializerInterface $serializer, EntityManagerInterface $manager){
        $promo = $repository_promo->findOnePromoGroupe($groupe, $id);
        $groupe = $request->getContent();
        $groupe = $serializer->decode($groupe, "json");
        $promo[0]->getGroupe()[0]->setStatut($groupe['groupe'][0]['statut']);

        $manager->persist($promo[0]);
        $manager->flush();
        return $this->json($promo, Response::HTTP_OK);
    }
}
