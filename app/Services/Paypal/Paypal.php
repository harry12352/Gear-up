<?php

namespace App\Services\Paypal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class Paypal
{
    private PayPalHttpClient $client;

    public function __construct()
    {
        $this->client = $this->setUpClient();
    }

    private function setUpClient()
    {
        $paypalConfig = config('services.paypal');
        if ($paypalConfig['testing']) {
            $environment = new SandboxEnvironment($paypalConfig['client_id'], $paypalConfig['secret']);
        } else {
            $environment = new ProductionEnvironment($paypalConfig['client_id'], $paypalConfig['secret']);
        }
        return new PayPalHttpClient($environment);
    }

    private function applicationContext(): array
    {
        $paypalConfig = config('services.paypal');
        return [
            'return_url' => url($paypalConfig['return_url']),
            'cancel_url' => url($paypalConfig['cancel_url'])
        ];
    }

    public function setupTransaction(float $unitAmount, string $currency)
    {
        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            'intent' => 'CAPTURE',
            'application_context' => $this->applicationContext(),
            'purchase_units' => [
                ['amount' => ['currency_code' => $currency, 'value' => $unitAmount]]
            ]
        ];
        return $this->client->execute($orderRequest);
    }

    public function captureTransaction(string $orderId)
    {
        $orderCatpure = new OrdersCaptureRequest($orderId);
        return $this->client->execute($orderCatpure);
    }
}
