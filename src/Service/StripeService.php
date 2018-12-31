<?php

namespace App\Service;


class StripeService
{

    public function Token($email, $prixTotal)
    {

        \Stripe\Stripe::setApiKey('sk_test_ScvgEh5PaD7Dqx7yL74t0K3p');

        $token  = $_POST['stripeToken'];

        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $prixTotal.'00',
            'currency' => 'eur'
        ));

        return $token;
    }
}
