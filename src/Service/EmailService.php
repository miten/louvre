<?php
/**
 * Created by PhpStorm.
 * User: miten
 * Date: 31/12/2018
 * Time: 23:56
 */

namespace App\Service;


use FontLib\TrueType\File;

class EmailService
{

    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
    }

    public function sendEmail($reservation)
    {

        $html = $this->templating->render('louvre/email.html.twig', array('reservation' => $reservation), 'text/html');

        $message = (new \Swift_Message('Hello Email'))
            ->setSubject('Billeterie musée du Louvre')
            ->setFrom('museedulouvre@example.com')
            ->setTo($reservation->getEmail())
            ->setBody($html);


        foreach ($reservation->getBillets() as $billet) {
            $file = new File('/web/pdf/'.$billet->getCode().'pdf');
            $pdf = new \Swift_Attachment($file, $billet->getCode().'.pdf', 'application/pdf');
            $message->attach($pdf);
        }


        $this->mailer->send($message);

        return true;
    }

}