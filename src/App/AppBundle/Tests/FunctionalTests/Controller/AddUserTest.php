<?php

namespace App\AppBundle\Tests\FunctionalTests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddUserTest extends WebTestCase {

    /**
     * @var Client
     */
    protected $client;

    protected $testEmail = 'testemail@mail.com';

    public function setUp() {
        $this->client = self::createClient();
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManagerForClass('AppAppBundle:User');
        $user = $em->getRepository('AppAppBundle:User')->findOneBy(array('email' => $this->testEmail));
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    public function getValidData() {
        return array(
            'email' => $this->testEmail,
            'dateOfBirth' => '05.10.1981.',
            'accountNumber' => '12345',
        );
    }

    public function getInvalidData() {
        return array(
            'email' => 'notEmail',
            'dateOfBirth' => 'notDate',
            'accountNumber' => 'notNumber',
        );
    }

    public function dataProviderTestWithInvalidData() {
        $return = array();
        $return[] = array(
            'email',
            'notEmail',
            'This value is not a valid email address.'
        );
        $return[] = array(
            'email',
            '',
            'Email can not be left blank'
        );
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
        $data = $this->getValidData();
        $data[$field] = $value;

        $crawler = $this->client->request('GET','/user');

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
            'Did not get an error for "'.$field.'" => "'.$value.'" with "'.$error.'" '.$reply->getContent()
        );
    }

    public function testSuccessInsert() {

        $data = $this->getValidData();

        $crawler = $this->client->request('GET','/user');

        $form = $crawler->selectButton('user_Save')->form();
        foreach ($data as $key=>$val) {
            $form['user['.$key.']'] = $val;
        }

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