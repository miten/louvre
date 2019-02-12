<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpKernel\KernelInterface;


class PdffService
{
    private $upload_directory;
    private $templating;

    public function __construct(\Twig_Environment $templating, $upload_directory)
    {
        $this->templating = $templating;
        $this->upload_directory = $upload_directory;
    }


    public function getPdf($reservation) {

        foreach ($reservation->getBillets() as $billet) {
            $billet->setCode();

            $html = $this->templating->render('louvre/billet.html.twig', array('reservation' => $reservation, 'billet' => $billet), 'text/html');

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdf_gen = $dompdf->output();

            file_put_contents($this->upload_directory . 'billet_'.$billet->getCode().'.pdf', $pdf_gen);


        }

    }

}
