<?php

namespace App\Controller;

use App\Entity\Calendrier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Component\HttpFoundation\Request;


class LouvreController extends AbstractController
{
    /**
     * @Route("/louvre", name="louvre")
     */
    public function index(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $disabledates = $this->getDoctrine()->getRepository(Calendrier::class)->getDisabledDates();
        $calendrier = new Calendrier();
        $dates = $calendrier->getDisabledDatesOnly($disabledates);



        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $form->getData();
            $reservation->setPrixTotal();
            $em->persist($reservation);
            $em->flush();

        }



        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'dates' => $dates
        ));
    }
}
