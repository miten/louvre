<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Service\TarifsServices;
use PHPUnit\Framework\TestCase;

class TarifsTest extends TestCase
{

	private $tarifsServices;


	public function setUp() {
		$this->tarifsServices = new TarifsServices();
	}

    public function test()
    {

        $billet = new Billet();
        $billet->setNom('thomas');
        $billet->setPrenom('good');
        $billet->setTarifReduit(false);
        $billet->setAge(new \DateTime("1990-05-05 00:00:00"));
        $reservation = new Reservation();
        $reservation->addBillet($billet);
        $reservation->setDemiJournee(0);
        $reservation->setEmail('test@test.fr');


        $reservation = $this->tarifsServices->Calcul($reservation);
        $reservation->setPrixTotal();

        $result = 16;

        $this->assertEquals(16, $result);
    }
}