<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Service\TarifsServices;
use PHPUnit\Framework\TestCase;

class TarifsTest extends TestCase
{
    public function test(TarifsServices $tarifsServices)
    {

        $billet = new Billet();
        $billet->setNom('thomas');
        $billet->setPrenom('good');
        $billet->setTarifReduit(false);
        $billet->setAge(new \DateTime(now()));
        $reservation = new Reservation();
        $reservation->addBillet($billet);
        $reservation->setDemiJournee(0);
        $reservation->setEmail('test@test.fr');


        $tarifsServices->Calcul($reservation);
        $reservation->setPrixTotal();

        $result = $reservation->getPrixTotal();

        $this->assertEquals(16, $result);
    }
}