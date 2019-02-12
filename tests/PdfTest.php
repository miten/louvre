<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Service\PdffService;
use App\Service\TarifsServices;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PdfTest extends WebTestCase
{

    private $pdfService;
    private $twig;
    private $tarifsServices;
    private $pdfTestFolder;

    public function setUp()
    {
        static::bootKernel();
        $this->pdfTestFolder = $this::$kernel->getProjectDir() . '/public/assets/pdf/';
        $this->twig = $this::$kernel->getContainer()->get('twig');
        $this->pdfService = new PdffService($this->twig, $this->pdfTestFolder);
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

        $this->pdfService->getPdf($reservation);

        foreach ($reservation->getBillets() as $billet) {
            $this->assertFileExists($this->pdfTestFolder.'billet_'.$billet->getCode().'.pdf');
        }
    }
}