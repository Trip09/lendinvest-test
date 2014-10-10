<?php

namespace App\AppBundle\Validator\ObjectInitializer;

use App\AppBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ObjectInitializerInterface;

class UserInitializer implements  ObjectInitializerInterface {

    /**
     * Initializes an object just before validation.
     *
     * @param object $object The object to validate
     *
     * @api
     */
    public function initialize($object)
    {
        if ($object instanceof User) {
            $this->correctAccountNumber($object);
        }
    }

    /**
     * Do correction of account number
     *
     * @param User $user
     */
    protected function correctAccountNumber(User $user) {
        $number = $user->getAccountNumber();
        $number = preg_replace('([^0-9])','', $number);
        $user->setAccountNumber($number);
    }
}