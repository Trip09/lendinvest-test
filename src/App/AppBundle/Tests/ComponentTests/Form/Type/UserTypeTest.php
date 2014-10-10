<?php

namespace App\AppBundle\Tests\ComponentTests\Form\Type;

use App\AppBundle\Entity\User;
use App\AppBundle\Form\Type\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    const EMAIL_FIELD = 'email';
    const DATE_OF_BIRTH_FIELD = 'dateOfBirth';
    const ACCOUNT_NUMBER_FIELD = 'accountNumber';

    public function testSubmitValidData()
    {
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $date = new \DateTime('now');
        $date->setTime(0,0,0);

        $formData = array(
            self::EMAIL_FIELD => 'test@mail.com',
            self::DATE_OF_BIRTH_FIELD => $date->format('d.m.Y.'),
            self::ACCOUNT_NUMBER_FIELD => '0123XYZ',

        );

        $type = new UserType();
        $form = $this->factory->create($type);

        $object = new User();
        $object->setEmail($formData[self::EMAIL_FIELD]);
        $object->setDateOfBirth($date);
        $object->setAccountNumber($formData[self::ACCOUNT_NUMBER_FIELD]);

        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}