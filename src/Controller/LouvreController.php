<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Service\PdffService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use App\Entity\Calendrier;
use App\Service\CalendrierService;

use App\Entity\Reservation;
use App\Form\ReservationType;




class LouvreController extends AbstractController
{
    /**
     * @Route("/louvre", name="louvre")
     */
    public function index(Request $request, CalendrierService $calendrierService)
    {

        $disabledDates = $calendrierService->getDates();

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $form->getData();
            $reservation->setPrixTotal();

            return $this->forward('App\Controller\LouvreController::paiement', array('reservation'  => $reservation));

        }


        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'dates' => $disabledDates
        ));
    }



    public function recapitulatif($reservation) {

        if (!isset($reservation)) {
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Aucune réservation'));
        }

        return $this->render('louvre/recap.html.twig', array(
            'reservation' => $reservation,
        ));
    }




    public function paiement($reservation, CalendrierService $calendrierService, StripeService $stripeService, PdffService $pdfService, EmailService $emailService) {

        $reservation->setToken('fff');

        if ($reservation->getToken() != null) {

        foreach ($reservation->getBillets() as $billet) {
            $billet->setCode();

        }

        $emailService->sendEmail($reservation);






        return $this->render('louvre/paiement.html.twig');

        }


        else {
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Echec paiement'));
        }



    }

}
