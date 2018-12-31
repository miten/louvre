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

        $dates = array();

        $disabledates = $this->repository->getDisabledDates();

        foreach ($disabledates as $date) {
            array_push($dates, $date->getDate());
        }

        return $dates;
    }

    public function AjoutVentes($reservation) {

        $ventes = $this->repository->findOneBy(array('date' => $reservation->getDate()));

        if ($ventes === null) {
            $ventes = new Calendrier();
            $ventes->setDate($reservation->getDate());
        }
        $ventes->setVentes(count($reservation->getBillets()));

        return $ventes;
    }
}