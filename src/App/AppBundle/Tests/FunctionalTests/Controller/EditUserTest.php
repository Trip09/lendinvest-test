<?php

namespace App\AppBundle\Tests\FunctionalTests\Controller;

use App\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditUserTest extends WebTestCase {

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

    public function dataProviderTestWithInvalidData() {
        $return = array();
        $return[] = array(
            'dateOfBirth',
            'notDate',
            'This value is not valid.'
        );
        $return[] = array(
            'dateOfBirth',
            '',
            'Date of birth can not be left blank'
        );
        $return[] = array(
            'accountNumber',
            'notNumber',
            'Account number must contain numbers'
        );
        $return[] = array(
            'accountNumber',
            '',
            'Account number must contain numbers'
        );
        $return[] = array(
            'dateOfBirth',
            '01.01.1900.',
            'Age must be between 18 and 100'
        );
        $return[] = array(
            'dateOfBirth',
            '01.01.'.date('Y').'.',
            'Age must be between 18 and 100'
        );
        return $return;
    }

    /**
     * @dataProvider dataProviderTestWithInvalidData
     */
    public function testWithInvalidData($field, $value, $error) {
        $data = array(
            'dateOfBirth' => $this->user->getDateOfBirth()->format('d.m.Y.'),
            'accountNumber' => $this->user->getAccountNumber(),
        );
        $data[$field] = $value;

        $crawler = $this->client->request('GET','/user/'.$this->user->getId().'/edit');

        $form = $crawler->selectButton('user_Save')->form();
        foreach ($data as $key=>$val) {
            $form['user['.$key.']'] = $val;
        }

        // submit the form
        $crawler = $this->client->submit($form);
        $reply = $this->client->getResponse();
        $this->assertInternalType(
            'integer',
            strpos($reply->getContent(), $error),
            'Did not get an error for "'.$field.'" => "'.$value.'" with "'.$error.'"'
        );
    }

    public function testSuccessInsert() {

        $crawler = $this->client->request('GET','/user/'.$this->user->getId().'/edit');

        $form = $crawler->selectButton('user_Save')->form();

        // submit the form
        $crawler = $this->client->submit($form);
        $reply = $this->client->getResponse();

        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManagerForClass('AppAppBundle:User');
        $user = $em->getRepository('AppAppBundle:User')->findOneBy(array('email' => $this->testEmail));

        $this->assertInstanceOf(
            'App\\AppBundle\\Entity\\User',
            $user,
            'Submitted user not in database'
        );

        $this->assertTrue(
            $this->client->getResponse()->isRedirect('/user/'.$user->getId()),
            'Redirection did not occur after successful creation of user'
        );
    }

    public function testNonExistingUser() {
        $crawler = $this->client->request('GET','/user/0/edit');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode(),'Did not get 404 on invalid edit');
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