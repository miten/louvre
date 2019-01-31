<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Service\PdffService;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function test(PdffService $pdfService, TarifsServices $tarifsServices)
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

        $pdfService->getPdf($reservation);

    }
}