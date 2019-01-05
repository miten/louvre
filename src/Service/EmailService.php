<?php
/**
 * Created by PhpStorm.
 * User: miten
 * Date: 31/12/2018
 * Time: 23:56
 */

namespace App\Service;


use FontLib\TrueType\File;
use Symfony\Component\HttpKernel\KernelInterface;

class EmailService
{

    private $mailer;
    private $templating;
    private $appKernel;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, KernelInterface $appKernel)
    {
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->appKernel = $appKernel;
    }

    public function sendEmail($reservation)
    {

        $publicDirectory =  $this->appKernel->getProjectDir();


        $html = $this->templating->render('louvre/email.html.twig', array('reservation' => $reservation), 'text/html');

        $message = (new \Swift_Message('Hello Email'))
            ->setSubject('Billeterie musée du Louvre')
            ->setFrom('museedulouvre@louvre.fr')
            ->setTo($reservation->getEmail())
            ->setBody($html, 'text/html');


        foreach ($reservation->getBillets() as $billet) {
            $file = $publicDirectory.'/assets/pdf/billet_'.$billet->getCode().'.pdf';
            $message->attach(\Swift_Attachment::fromPath($file, 'application/pdf'));
        }



        $this->mailer->send($message);

        return true;
    }

}