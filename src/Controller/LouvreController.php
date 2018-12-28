<?php

namespace App\Controller;

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
        $disabledates = ['29/12/2018', '01/01/2019', '30/12/2018'];

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $form->getData();

            $em = $this->getDoctrine()->getManager();


        }



        return $this->render('louvre/index.html.twig', array(
            'form' => $form->createView(),
            'dates' => $disabledates
        ));
    }
}
