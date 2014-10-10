<?php

namespace App\AppBundle\Tests\UnitTests\Entity;

use App\AppBundle\Tests\UnitTests\AbstractUnitTest;
use App\AppBundle\Entity\User;

class UserTest extends AbstractUnitTest
{

    /**
     * @var User
     */
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    public function testGetId()
    {
        $idValue = 123;
        $reflectionProperty = new \ReflectionProperty(get_class($this->user), 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->user, $idValue);
        $this->assertEquals($idValue, $this->user->getId(), 'User::getId is not returning a valid value');
    }

    public function testSetEmail()
    {
        $email = 'email@mail.com';
        $this->assertAttributeNotEquals(
            $email,
            'email',
            $this->user,
            'User::$email should not preset to test value'
        );
        $this->user->setEmail($email);
        $this->assertAttributeEquals(
            $email,
            'email',
            $this->user,
            'User::setEmail is not setting expected value'
        );
    }

    /**
     * @depends testSetEmail
     */
    public function testGetEmail()
    {
        $email = 'email@mail.com';
        $this->user->setEmail($email);
        $this->assertEquals(
            $email,
            $this->user->getEmail(),
            'User::getEmail is not returning a valid value'
        );
    }

    public function testSetDateOfBirth()
    {
        $dateOfBirth = new \DateTime('1981-10-05 12:00:00');
        $this->assertAttributeNotEquals(
            $dateOfBirth,
            'dateOfBirth',
            $this->user,
            'User::$dateOfBirth should not preset to test value'
        );
        $this->user->setDateOfBirth($dateOfBirth);
        $this->assertAttributeEquals(
            $dateOfBirth,
            'dateOfBirth',
            $this->user,
            'User::setDateOfBirth is not setting expected value'
        );
    }

    /**
     * @depends testSetDateOfBirth
     */
    public function testGetDateOfBirth()
    {
        $dateOfBirth = new \DateTime('1981-10-05 12:00:00');
        $this->user->setDateOfBirth($dateOfBirth);
        $this->assertEquals(
            $dateOfBirth,
            $this->user->getDateOfBirth(),
            'User::getDateOfBirth is not returning a valid value'
        );
    }

    public function testSetAccountNumber()
    {
        $accountNumber = 'Account number';
        $this->assertAttributeNotEquals(
            $accountNumber,
            'accountNumber',
            $this->user,
            'User::$accountNumber should not preset to test value'
        );
        $this->user->setAccountNumber($accountNumber);
        $this->assertAttributeEquals(
            $accountNumber,
            'accountNumber',
            $this->user,
            'User::setAccountNumber is not setting expected value'
        );
    }

    /**
     * @depends testSetAccountNumber
     */
    public function testGetAccountNumber()
    {
        $accountNumber = 'Account number';
        $this->user->setAccountNumber($accountNumber);
        $this->assertEquals(
            $accountNumber,
            $this->user->getAccountNumber(),
            'User::getAccountNumber is not returning a valid value'
        );
    }

    public function dataProviderTestGetAge() {
        $return = array();

        $date1 = new \DateTime('now');
        $date1->setDate(date('Y')-100, date('m'), date('d'));
        $date1->modify('-1 day');
        $return[] = array($date1, 99);


        $date2 = new \DateTime('now');
        $date2->setDate(date('Y')-100, date('m'), date('d'));
        $date2->modify('+1 day');
        $return[] = array($date2, 100);


        $date3 = new \DateTime('now');
        $date3->setDate(date('Y')-100, date('m'), date('d'));
        $return[] = array($date3, 100);

        return $return;
    }

    /**
     * @depends testSetDateOfBirth
     * @depends testGetDateOfBirth
     * @dataProvider dataProviderTestGetAge
     */
    public function testGetAge(\DateTime $dateOfBirth, $age)
    {
        $this->user->setDateOfBirth($dateOfBirth);
        $this->assertEquals($age, $this->user->getAge(), 'User::getAge is not calculating age correctly '.$this->user->getDateOfBirth()->format('Y-m-d'));
    }

}