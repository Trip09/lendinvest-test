<?php

namespace App\AppBundle\Tests\FunctionalTests\Twig\Extension;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TwigExtensionTest extends KernelTestCase
{

    public function setUp() {
        parent::setUp();
        self::bootKernel();
    }

    public function testTwigHasExtension() {
        $extension = self::$kernel->getContainer()->get('twig')->getExtension('user_risk_calculator');
        $this->assertInstanceOf(
            'App\\AppBundle\\Twig\\Extension\\UserRiskExtension',
            $extension,
            'Not a valid extension for user_risk_calculator in Twig'
        );
    }

}