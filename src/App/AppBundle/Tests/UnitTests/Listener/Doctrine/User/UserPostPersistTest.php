<?php

namespace App\AppBundle\Tests\UnitTests\Listener\Doctrine\User;

use App\AppBundle\Tests\UnitTests\AbstractUnitTest;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use App\AppBundle\Entity\User;
use App\AppBundle\Listener\Doctrine\User\UserPostPersist;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;


class UserPostPersistTest extends AbstractUnitTest {

    /**
     * @var LifecycleEventArgs|MockObject
     */
    protected $lifeCycleArgs;

    /**
     * @var ObjectManager|MockObject
     */
    protected $entityManager;

    /**
     * @var UserPostPersist
     */
    protected $listener;

    public function setUp() {
        parent::setUp();

        $this->lifeCycleArgs = $this
            ->getMockBuilder('Doctrine\\Common\\Persistence\\Event\\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->entityManager = $this
            ->getMockBuilder('Doctrine\\Common\\Persistence\\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->listener = new UserPostPersist();
    }

    /**
     * @return User|MockObject
     */
    public function getUserMock() {

        return $this
            ->getMockBuilder('App\\AppBundle\\Entity\\User')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testWithValidObject() {
        $user = $this->getUserMock();
        $user
            ->expects($this->once())
            ->method('getId')
            ->willReturn('1234')
        ;

        $user
            ->expects($this->once())
            ->method('getAccountNumber')
            ->willReturn('56789')
        ;

        $this->lifeCycleArgs
            ->expects($this->once(0))
            ->method('getObject')
            ->will($this->returnValue($user))
        ;
        $this->lifeCycleArgs
            ->expects($this->at(1))
            ->method('getObjectManager')
            ->will($this->returnValue($this->entityManager))
        ;

        $user->expects($this->once())
            ->method('setReference')
            ->with('5678-0000001234')
        ;

        $this->entityManager->expects($this->once())->method('flush');

        $this->listener->postPersist($this->lifeCycleArgs);
    }

    public function testWithInvalidObject() {
        $object = new \stdClass();

        $this->lifeCycleArgs
            ->expects($this->once(0))
            ->method('getObject')
            ->will($this->returnValue($object))
        ;
        $this->lifeCycleArgs
            ->expects($this->never())
            ->method('getObjectManager')
            ->will($this->returnValue($this->entityManager))
        ;

        $this->entityManager->expects($this->never())->method('flush');

        $this->listener->postPersist($this->lifeCycleArgs);
    }

}