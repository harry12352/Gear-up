<?php

namespace App\Services\Shipping\Usps;

class Shipment
{
    public array $packages;
    public array $origin;
    public array $destination;

    public function __construct(array $origin, array $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function addPackage($package)
    {
        $this->packages[] = $package;

        return $this;
    }

    public function getPackages()
    {
        return $this->packages;
    }

}
