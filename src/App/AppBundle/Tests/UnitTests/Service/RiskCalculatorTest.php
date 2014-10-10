<?php

namespace App\AppBundle\Tests\UnitTests\Service;

use App\AppBundle\Service\RiskCalculator;
use App\AppBundle\Tests\UnitTests\AbstractUnitTest;
use App\AppBundle\Entity\User;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class RiskCalculatorTest extends AbstractUnitTest
{

    /**
     * @var RiskCalculator
     */
    protected $calculator;

    /**
     * @var User|MockObject
     */
    protected $userMock;

    public function setUp() {
        parent::setUp();
        $this->calculator = new RiskCalculator();
        $this->userMock = $this
            ->getMockBuilder('App\\AppBundle\\Entity\\User')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function dataProviderTestRiskCalculatorCalculateDeathRisk() {
        $return = array();
        for ($i = 18; $i<= 99; $i++) {
            $return[] =  array($i, (log($i) -1) * 100);
        }
        return $return;
    }

    /**
     * @param int $age
     * @param float $result
     * @dataProvider dataProviderTestRiskCalculatorCalculateDeathRisk
     */
    public function testRiskCalculatorCalculateDeathRisk($age, $result) {
        $this->userMock->expects($this->once())->method('getAge')->will($this->returnValue($age));
        $res = $this->calculator->calculateUserDeathRisk($this->userMock);
        $this->assertEquals(round($result,2), round($res, 2), 'Death risk not calculated as expected');
    }

    public function dataProviderTestRiskCalculatorCalculateMortgageRate() {
        $return = array();
        for ($i = 18; $i<= 99; $i++) {
            $rate =  (1+5-3+100-300*log(log($i - 1)))/10;
            $return[] =  array($i,$rate);
        }
        return $return;
    }

    /**
     * @param int $age
     * @param float $result
     * @dataProvider dataProviderTestRiskCalculatorCalculateMortgageRate
     */
    public function testRiskCalculatorCalculateMortgageRate($age, $result) {
        $this->userMock->expects($this->once())->method('getAge')->will($this->returnValue($age));
        $res = $this->calculator->calculateUserMortgageRate($this->userMock);
        $this->assertEquals(round($result,2), round($res, 2), 'Mortgage rate not calculated as expected');
    }


}