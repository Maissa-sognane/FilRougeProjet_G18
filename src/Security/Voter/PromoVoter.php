<?php

namespace App\Security\Voter;

use App\Entity\Promo;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PromoVoter extends Voter
{
    protected function supports($attribute, $subject)
    {

        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        return in_array($attribute, ['EDIT', 'VIEW', 'VIEW_CM']);
        //  && $subject instanceof GroupeCompetences;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        /** @var Promo $subject */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...

        switch ($attribute) {
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                return $user->getRoles()[0] === "ROLE_ADMIN";
                break;
            case 'VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                return $user->getRoles()[0] === "ROLE_ADMIN" || $user->getRoles()[0] === "ROLE_FORMATEUR" ;
                break;
            case 'VIEW_CM':
                return $user->getRoles()[0] === "ROLE_CM";
                break;
        }

        return false;
    }
}
