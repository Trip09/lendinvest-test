<?php

namespace App\AppBundle\Tests\FunctionalTests\Controller;

use App\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DisplayUserTest extends WebTestCase {

    /**
     * @var Client
     */
    protected $client;

    protected $testEmail = 'testemail@mail.com';

    /**
     * @var User
     */
    protected $user;

    public function setUp() {
        $this->client = self::createClient();
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManagerForClass('AppAppBundle:User');
        $user = $em->getRepository('AppAppBundle:User')->findOneBy(array('email' => $this->testEmail));
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        $newUser = new User();
        $newUser->setEmail($this->testEmail);
        $newUser->setDateOfBirth(new \DateTime('1984-01-01'));
        $newUser->setAccountNumber('1234');
        $this->user = $newUser;
        $em->persist($newUser);
        $em->flush();
    }


    public function testNonExistingUser() {
        $crawler = $this->client->request('GET','/user/0');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode(),'Did not get 404 on invalid display');
    }

    public function testExistingUser() {
        $crawler = $this->client->request('GET','/user/'.$this->user->getId());
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(),'Did not get 200 on valid display');
        $content = $response->getContent();
        $this->assertInternalType(
            'integer',
            strpos($content, 'Hi '.$this->user->getEmail()),
            '"Hi email" not found on display page'
        );
        $this->assertInternalType(
            'integer',
            strpos($content, 'Your reference is '.$this->user->getReference()),
            '"Your reference" not found on display page'
        );
        $this->assertInternalType(
            'integer',
            strpos($content, 'Based on your age '.$this->user->getAge().' your risk to die is '),
            '"Your age" not found on display page'
        );

        $calculator = self::$kernel->getContainer()->get('app.risk_calculator');
        $deathRisk = round($calculator->calculateUserDeathRisk($this->user));
        $mortgage = round($calculator->calculateUserMortgageRate($this->user));

        $this->assertInternalType(
            'integer',
            strpos($content, 'your risk to die is '.$deathRisk.'%'),
            '"Your death risk" not found on display page'
        );
        $this->assertInternalType(
            'integer',
            strpos($content, ' Mortgage risk: '.$mortgage.'%'),
            '"Mortgage" not found on display page'
        );
    }

    public function tearDown() {
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManagerForClass('AppAppBundle:User');
        $user = $em->getRepository('AppAppBundle:User')->findOneBy(array('email' => $this->testEmail));
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        parent::tearDown();
    }
}