<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Service\PdffService;
use PHPUnit\Framework\TestCase;


class PdfTest extends TestCase
{

    private $pdfService;

    public function setUp()
    {

        $this->pdfService = new PdffService();
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

        $this->pdfService->getPdf($reservation);

        foreach ($reservation->getBillets() as $billet) {
            $this->assertFileExists('../assets/pdf/'.$billet->getCode().'.pdf');
        }
    }
}