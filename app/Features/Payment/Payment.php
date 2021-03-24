<?php

namespace App\Features\Payment;

use App\Services\Paypal\Paypal;
use App\Features\Payment\Orders\ProductOrder;

class Payment
{
    private Paypal $paypal;

    public function __construct(Paypal $paypal)
    {
        $this->paypal = $paypal;
    }

    public function createOrder(ProductOrder $productOrder): string
    {
        /** @var stdClass */
        $transaction = $this->paypal->setupTransaction($productOrder->getAmount(), $productOrder->getCurrency());

        return $transaction->result->id;
    }

    public function orderPaymentApproveUri(string $orderId)
    {
        // FIXME > Implement this correctly.
        return "https://www.sandbox.paypal.com/checkoutnow?token={$orderId}";
    }

    public function approveOrder(string $orderId): array
    {
        /** @var stdClass */
        $transaction = $this->paypal->captureTransaction($orderId)->result;
        return [
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
            'orderId' => $orderId,
            'full_transaction' => (array) $transaction
        ];
    }
}
