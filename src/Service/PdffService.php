<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpKernel\KernelInterface;


class PdffService
{
    private $appKernel;
    private $templating;

    public function __construct(\Twig_Environment $templating, KernelInterface $appKernel)
    {
        $this->templating = $templating;
        $this->appKernel = $appKernel;
    }


    public function getPdf($reservation) {

        $publicDirectory =  $this->appKernel->getProjectDir();


        foreach ($reservation->getBillets() as $billet) {
            $billet->setCode();

            $html = $this->templating->render('louvre/facture.html.twig', array('reservation' => $reservation, 'billet' => $billet), 'text/html');

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdf_gen = $dompdf->output();

            file_put_contents($publicDirectory . '/web/pdf/billet_'.$billet->getCode().'.pdf', $pdf_gen);


        }

    }

}
