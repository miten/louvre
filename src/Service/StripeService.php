<?php

namespace App\Service;


use function Symfony\Component\DependencyInjection\Tests\Fixtures\factoryFunction;

class StripeService
{

    public function Stripe($reservation, $token) {
        $prixTotal = $reservation->getPrixTotal();
        $email = $reservation->getEmail();

        \Stripe\Stripe::setApiKey('sk_test_ScvgEh5PaD7Dqx7yL74t0K3p');

        \Stripe\Customer::create(array('email' => $email,));

        try {
            \Stripe\Charge::create(array(
                'source' => $token,
                'amount'   => $prixTotal.'00',
                'currency' => 'eur',
                'description' => 'Musee du Louvre'
            ));

        } catch(\Stripe\Error\Card $e) {

            return false;
        }

        return $token;
    }
}
