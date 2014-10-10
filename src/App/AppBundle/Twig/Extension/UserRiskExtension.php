<?php

namespace App\AppBundle\Twig\Extension;

use App\AppBundle\Entity\User;
use App\AppBundle\Service\RiskCalculator;

class UserRiskExtension extends \Twig_Extension {

    /**
     * @var RiskCalculator
     */
    protected $calculator;

    public function __construct(RiskCalculator $calculator) {
        $this->calculator = $calculator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_risk_calculator';
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('user_death_risk', array($this,'userDeathRisk')),
            new \Twig_SimpleFunction('user_mortgage_risk', array($this,'userMortgageRisk')),
        );
    }

    public function userDeathRisk(User $user) {
        return $this->calculator->calculateUserDeathRisk($user);
    }

    public function userMortgageRisk(User $user) {
        return $this->calculator->calculateUserMortgageRate($user);
    }
}