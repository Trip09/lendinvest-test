<?php

namespace App\AppBundle\Tests\UnitTests\Validator\ObjectInitializer;

use App\AppBundle\Tests\UnitTests\AbstractUnitTest;
use App\AppBundle\Validator\ObjectInitializer\UserInitializer;

class UserInitializerTest extends AbstractUnitTest
{
    /**
     * @var UserInitializer
     */
    protected $initializer;

    public function setUp() {
        $this->initializer = new UserInitializer();
    }

    public function dataProviderTestAccountNumberCorrection() {
        return array(
            array('0123','0123'),
            array('A1B2C3','123'),
            array('4.5-6 ', '456'),
        );
    }

    /**
     * @param string $input
     * @param string $correction
     * @dataProvider dataProviderTestAccountNumberCorrection
     */
    public function testAccountNumberCorrection($input, $correction) {
        $userMock = $this
            ->getMockBuilder('App\\AppBundle\\Entity\\User')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userMock
            ->expects($this->at(0))
            ->method('getAccountNumber')
            ->will($this->returnValue($input))
        ;

        $userMock
            ->expects($this->at(1))
            ->method('setAccountNumber')
            ->with($correction)
        ;

        $this->initializer->initialize($userMock);
    }
}