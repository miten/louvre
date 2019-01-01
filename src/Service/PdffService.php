<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;


class PdffService
{

    public function __construct(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }


    public function getPdf($reservation, $billet) {

        $html = $this->templating->render('louvre/facture.html.twig', array('reservation' => $reservation, 'billet' => $billet), 'text/html');

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/../web/pdf/billet_'.$billet->getCode().'.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Send some text response
        return true;
    }


}
