<?php

namespace App\AppBundle\Tests\UnitTests\Twig\Extension;

use App\AppBundle\Tests\UnitTests\AbstractUnitTest;
use App\AppBundle\Twig\Extension\UserRiskExtension;
use App\AppBundle\Service\RiskCalculator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;


class UserRiskExtensionTest extends AbstractUnitTest
{

    /**
     * @var RiskCalculator|MockObject
     */
    protected $calculatorMock;

    /**
     * @var UserRiskExtension
     */
    protected $extension;

    public function setUp() {
        parent::setUp();
        $this->calculatorMock = $this
            ->getMockBuilder('App\\AppBundle\\Service\\RiskCalculator')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->extension = new UserRiskExtension($this->calculatorMock);
    }

    public function dataProviderTestIfDeathFunctionExists() {
        return array(
            array('user_death_risk', 'userDeathRisk'),
            array('user_mortgage_risk','userMortgageRisk'),
        );
    }

    /**
     * @param string $twigFunction
     * @param string $methodName
     * @dataProvider dataProviderTestIfDeathFunctionExists
     */
    public function testIfDeathFunctionExists($twigFunction, $methodName) {

        foreach ($this->extension->getFunctions() as $function) {
            /**
             * @var \Twig_SimpleFunction $function
             */
            if ($function->getName() == $twigFunction) {
                $this->assertEquals(
                    array($this->extension, $methodName),
                    $function->getCallable(),
                    'Twig extension does not map function "'.$twigFunction.'" to it\'s method "'.$methodName.'"'
                );
                return;
            }
        };

        $this->fail('Did not find twig function "'.$twigFunction.'"');
    }


    public function dataProviderTestFunctionForward() {
        return array(
            array('userDeathRisk', 'calculateUserDeathRisk'),
            array('userMortgageRisk', 'calculateUserMortgageRate'),
        );
    }

    /**
     * @param string $extensionFunction
     * @param string $calculatorFunction
     * @dataProvider dataProviderTestFunctionForward
     */
    public function testFunctionForward($extensionFunction, $calculatorFunction) {
        $userMock = $this
            ->getMockBuilder('App\\AppBundle\\Entity\\User')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $return = md5(microtime());
        $this->calculatorMock
            ->expects($this->once())
            ->method($calculatorFunction)
            ->with($userMock)
            ->willReturn($return)
        ;

        $res = $this->extension->$extensionFunction($userMock);
        $this->assertEquals(
            $return,
            $res,
            'Twig Extension for risk calculator method "' .
                $extensionFunction . '" did not return value by calculator service'
        );
    }
}