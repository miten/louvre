<?php

namespace App\Service;


use App\Entity\Calendrier;
use App\Repository\CalendrierRepository;
use Doctrine\ORM\EntityManagerInterface;

class CalendrierService


{

    private $repository;


    public function __construct(EntityManagerInterface $em)
    {

        $this->repository = $em->getRepository(Calendrier::class);
    }


    public function getDates() {

        date_default_timezone_set('Europe/Paris');

        $heure = date('H');
        $year = date('Y');



        // Initialisation du tableau des dates à désactiver
        $dates = array();

        // Liste des jours fériés

        $joursferies = ['25-12-'.$year, '01-05-'.$year, '01-11-'.$year];


        // Si 18h passé, billeterie fermée le jour même

        if ($heure > 18) {
            array_push($dates, Date('d-m-Y'));
        }


        // Recupération des ventes de plus de 1000 billets en base de donnée
        $disabledates = $this->repository->getDisabledDates();



        // Ajout des jours désactivés au tableau
        foreach ($disabledates as $date) {
            array_push($dates, $date->getDate());
        }

        foreach ($joursferies as $jourferie) {
            array_push($dates, $jourferie);
        }

        return $dates;
    }

    public function AjoutVentes($reservation) {


        // Récupération des ventes à la date de la réservation en base de donnée
        $ventes = $this->repository->findOneBy(array('date' => $reservation->getDate()));


        // Si aucun objet vente n'a été trouvé (= ventes nulle à cette date) on instancie une nouvelle vente

        if ($ventes === null) {
            $ventes = new Calendrier();
            $ventes->setDate($reservation->getDate());
        }

        // On ajoute le nombre de ventes à l'objet

        $ventes->setVentes(count($reservation->getBillets()));

        return $ventes;
    }
}