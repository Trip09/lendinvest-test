<?php

namespace App\AppBundle\Service;

use App\AppBundle\Entity\User;

class RiskCalculator {

    public function calculateUserDeathRisk(User $user) {
        return (log($user->getAge()) -1) * 100;
    }

    public function calculateUserMortgageRate(User $user) {
        return (1+5-3+100-300*log(log($user->getAge() - 1)))/10;
    }
}