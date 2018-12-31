<?php

namespace App\Controller;

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

            $em = $this->getDoctrine()->getManager();

            $reservation = $form->getData();
            $reservation->setPrixTotal();


            $ventes = $calendrierService->AjoutVentes($reservation);



            $em->merge($ventes);
            $em->flush();
            $em->persist($reservation);

        }



        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'dates' => $disabledDates
        ));
    }


    public function paiement(Request $request, CalendrierService $calendrierService) {


        $reservation = 'lol';


        return $this->render('louvre/paiement.html.twig', array(
            'reservation' => $reservation,
        ));
    }
}
