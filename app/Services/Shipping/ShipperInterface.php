<?php

namespace App\Services\Shipping;

interface ShipperInterface
{
    public function getRate(Shipment $shipment);
    public function createLabel();
}
