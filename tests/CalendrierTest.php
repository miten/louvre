<?php

namespace App\Tests;

use App\Entity\Billet;
use App\Entity\Calendrier;
use App\Entity\Reservation;
use App\Service\CalendrierService;
use PHPUnit\Framework\TestCase;

class CalendrierTest extends TestCase
{
    public function test(CalendrierService $calendrierService)
    {
        $dates = $calendrierService->getDates();
        $this->assertEquals(5, count($dates));
    }
}