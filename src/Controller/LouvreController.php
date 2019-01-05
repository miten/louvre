<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Service\PdffService;
use App\Service\StripeService;
use App\Service\TarifsServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

        // Recupère les dates invalides
        $disabledDates = $calendrierService->getDates();


        // Crée un formulaire de reservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // Recupère les données du formulaire et renvoie a la page recapitulatif lors de l'envoi
            $reservation = $form->getData();
            return $this->forward('App\Controller\LouvreController::recapitulatif', array('reservation'  => $reservation));

        }

        return $this->render('louvre/index.html.twig', array('form' => $form->createView(), 'dates' => $disabledDates));
    }



    public function recapitulatif($reservation, TarifsServices $tarifsServices) {

        if (isset($reservation)) {
            // Calcul des prix
            $tarifsServices->Calcul($reservation);

            // Ajout de la reservation en $session
            $session = new Session();
            $session->set('reservation',$reservation);

            return $this->render('louvre/recap.html.twig', array('reservation' => $reservation));
        }


        else {
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Aucune réservation'));
        }

    }



    public function paiement(CalendrierService $calendrierService, StripeService $stripeService, PdffService $pdfService, EmailService $emailService) {

        $session = new Session();
        $reservation = $session->get('reservation');



        if (isset($reservation)) {
            // Connection base de donnée
            $em = $this->getDoctrine()->getManager();

            // Création des pdf
            $pdfService->getPdf($reservation);


            // Paiement stripe
            $token = $stripeService->Stripe($reservation, $_POST['stripeToken']);
            $reservation->setToken($token);


            // Si le paiement à bien été effectué
            if ($reservation->getToken() != null) {

                // Envoie l'email de reservation
                $emailService->sendEmail($reservation);

                // Ajoute les ventes à la base de donnée
                $ventes = $calendrierService->AjoutVentes($reservation);
                $em->persist($ventes);

                // Ajoute les reservations à la base de donnée
                $em->persist($reservation);

                // Envoie les données
                $em->flush();
                $session->clear();
                return $this->render('louvre/paiement.html.twig', array('email' => $reservation->getEmail()));
            }

            // Echec paiement page erreur
            else {
                $session->clear();
                return $this->render('louvre/erreur.html.twig', array('erreur' => 'Echec paiement'));
            }
        }


        // Pas de réservation page erreur
        else {
            return $this->render('louvre/erreur.html.twig', array('erreur' => 'Pas de réservation'));
        }


    }

}
