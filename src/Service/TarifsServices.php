<?php

namespace App\Service;


class TarifsServices
{

    public function Calcul($reservation)
    {
        $nombreAdulte = 0;
        $nombreEnfant =0;

        foreach ($reservation->getBillets() as $billet) {

            switch ($billet) {

                case ($billet->getTarifReduit() === true):
                    $billet->setPrix('10');
                    $billet->setTarif('reduit');
                    break;

                case ($billet->getAge() >= 60):
                    $billet->setPrix('12');
                    $billet->setTarif('senior');
                    break;

                case ($billet->getAge() > 12 && $billet->getAge() < 60):
                    $billet->setPrix('16');
                    $billet->setTarif('normal');
                    $nombreAdulte + 1;
                    break;

                case ($billet->getAge() >= 4 && $billet->getAge() <= 12):
                    $billet->setPrix('8');
                    $billet->setTarif('enfant');
                    $nombreEnfant + 1;
                    break;

                case ($billet->getAge() < 4):
                    $billet->setPrix('0');
                    $billet->setTarif('jeune enfant');
                    break;

            }
        }

        $reservation->setPrixTotal();

    }


}
