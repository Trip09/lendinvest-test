<?php

namespace App\AppBundle\Tests\FunctionalTests\Validator\ObjectInitializer;

use App\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TwigExtensionTest extends KernelTestCase
{

    public function setUp() {
        parent::setUp();
        self::bootKernel();
    }


    public function testValidatorHasExtension() {
        $initializerMock = $this
            ->getMockBuilder('App\\AppBundle\\Validator\\ObjectInitializer\\UserInitializer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userMock = new User();
        self::$kernel->getContainer()->set('app.object_initializer.user_initializer', $initializerMock);

        $initializerMock->expects($this->atLeastOnce())->method('initialize')->with($userMock);

        $validator = self::$kernel->getContainer()->get('validator');
        $validator->validate($userMock);
    }

}