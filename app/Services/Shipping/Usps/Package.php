<?php

namespace App\Services\Shipping\Usps;

class Package
{
    protected array $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    public function __get(string $name)
    {
        return $this->details[$name] ?? null;
    }
}
