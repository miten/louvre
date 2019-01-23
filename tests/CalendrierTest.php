<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class TarifsTest extends TestCase
{
    public function test()
    {

        $billet = new Billet();
        $billet->setNom('thomas');
        $billet->setPrenom('good');
        $billet->setTarifReduit(false);
        $billet->setAge(26);
        $reservation = new Reservation();
        $reservation->addBillet($billet);
        $reservation->setDemiJournee(0);
        $reservation->setEmail('test@test.fr');
        $reservation->setDemiJournee(1);

        $reservation->setPrixTotal();

        $result = $reservation->getPrixTotal();

        $this->assertEquals(16, $result);
    }
}