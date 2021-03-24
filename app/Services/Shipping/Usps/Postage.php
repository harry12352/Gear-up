<?php

namespace App\Services\Shipping\Usps;

class Postage
{
    protected $mailService;
    protected $rate;

    public function __construct(string $mailService, float $rate)
    {
        $this->mailService = $mailService;
        $this->rate = $rate;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getPostage(): string
    {
        return $this->mailService;
    }
}
