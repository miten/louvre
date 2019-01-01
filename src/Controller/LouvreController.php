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
use Symfony\Component\HttpFoundation\Session\Session;


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
            return $this->forward('App\Controller\LouvreController::recapitulatif', array('reservation'  => $reservation));

        }

        return $this->render('louvre/index.html.twig', array('form' => $form->createView(), 'dates' => $disabledDates));
    }



    public function recapitulatif($reservation) {

        if (!isset($reservation)) {
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Aucune réservation'));
        }


        else {
            $session = new Session();
            $session->set('reservation',$reservation);
            return $this->render('louvre/recap.html.twig', array('reservation' => $reservation));
        }

    }



    public function paiement(CalendrierService $calendrierService, StripeService $stripeService, PdffService $pdfService, EmailService $emailService) {


        $session = new Session();
        $reservation = $session->get('reservation');

        $em = $this->getDoctrine()->getManager();

        foreach ($reservation->getBillets() as $billet) {
            $billet->setCode();
        }

        $token = $stripeService->Stripe($reservation, $_POST['stripeToken']);
        $reservation->setToken($token);


        if ($reservation->getToken() != null) {

            $emailService->sendEmail($reservation);


            $ventes = $calendrierService->AjoutVentes($reservation);
            $em->persist($reservation);
            $em->persist($ventes);
            $em->flush();
            $session->clear();
            return $this->render('louvre/paiement.html.twig');
        }


        else {
            $session->clear();
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Echec paiement'));
        }


    }

}
