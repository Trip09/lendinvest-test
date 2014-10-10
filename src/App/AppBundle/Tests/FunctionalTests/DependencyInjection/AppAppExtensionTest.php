<?php

namespace App\AppBundle\Tests\FunctionalTests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppAppExtensionTest extends KernelTestCase {

    public function setUp() {
        parent::setUp();
        self::bootKernel();
    }

    public function dataProviderTestServiceExistence() {
        return array(
            array(
                'app.object_initializer.user_initializer',
                'App\AppBundle\Validator\ObjectInitializer\UserInitializer'
            ),
            array(
                'app.doctrine_event.user.post_persist',
                'App\AppBundle\Listener\Doctrine\User\UserPostPersist'
            ),
            array(
                'app.risk_calculator',
                'App\AppBundle\Service\RiskCalculator'
            ),
            array(
                'app.twig.risk_calculator',
                'App\AppBundle\Twig\Extension\UserRiskExtension'
            ),
        );
    }

    /**
     * @param string $serviceName
     * @param string $serviceClass
     * @dataProvider dataProviderTestServiceExistence
     */
    public function testServiceExistence($serviceName, $serviceClass) {
        $container = self::$kernel->getContainer();
        $this->assertTrue($container->has($serviceName), 'Service "'.$serviceName.'" not found in kernel');
        $service = $container->get($serviceName);

        $this->assertInstanceOf(
            $serviceClass,
            $service,
            'Service "'.$serviceName.'" not instance of "'.$serviceClass.'"'
        );
    }

}