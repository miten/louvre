<?php

namespace App\Tests;

use App\Service\CalendrierService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Tests\KernelTest;

class CalendrierTestTest extends KernelTestCase
{

    private $entityManager;
    private $calendrierService;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->calendrierService = new CalendrierService($this->entityManager);
    }



    public function test()
    {
        date_default_timezone_set('Europe/Paris');
        $heure = date('H');
        $result = $this->calendrierService->getDates();

        if ($heure > 18) {
            $this->assertEquals(16, count($result));
        }

        else {
            $this->assertEquals(15, count($result));
        }




    }



    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}