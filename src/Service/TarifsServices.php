<?php

namespace App\Service;


class TarifsServices
{

    public function Calcul($billet)
    {

        $nombreAdulte = 0;
        $nombreEnfant =0;

        switch ($billet) {

            case ($billet->getTarifReduit() === true):
                $billet->setPrix('10');
                $billet->setTarif('Reduit');
                break;

            case ($billet->getAge() >= 60):
                $billet->setPrix('12');
                $billet->setTarif('Senior');
                break;

            case ($billet->getAge() > 12 && $billet->getAge() < 60):
                $billet->setPrix('16');
                $billet->setTarif('Normal');
                $nombreAdulte++;
                break;

            case ($billet->getAge() >= 4 && $billet->getAge() <= 12):
                $billet->setPrix('8');
                $billet->setTarif('Enfant');
                ++$nombreEnfant;
                break;

            case ($billet->getAge() < 4):
                $billet->setPrix('0');
                $billet->setTarif('Jeune enfant');
                break;

        }

        return $billet;
    }

}
