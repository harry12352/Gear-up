<?php

namespace App\Http\Controllers;

use App\Features\Payment\Orders\ProductOrder;
use App\Features\Payment\Payment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function buy(User $user, Product $product, Payment $payment)
    {
        $orderId = $payment->createOrder(new ProductOrder($product->price));
        // Saving the order id in session for capturing the payment.
        session(['orderId' => $orderId, 'productId' => $product->id]);

        return redirect($payment->orderPaymentApproveUri($orderId));
    }

    public function accept(Payment $payment)
    {
        if (!session()->has('orderId')) {
            return redirect(route('payment.failed'));
        }
        $orderId = session()->pull('orderId');
        $orderDetails = $payment->approveOrder($orderId);
        $order = new Order([
            'order_id' => $orderId,
            'product_id' => session()->pull('productId'),
            'user_id' => Auth::user()->id,
            'transaction_id' => $orderDetails['transaction_id'],
            'transaction_details' => $orderDetails['full_transaction']
        ]);
        $order->save();
        return redirect(route('payment.success'))->with(['order' => $order]);
    }

    public function success()
    {
        $order = session()->pull('order');
        return view('payment.success', compact("order"));
    }

    public function failed()
    {
        return view('payment.error');
    }

    public function cancel()
    {
        // TODO: Get product title here
        $productTitle = 'Dolo emit sit asulm';
        return redirect(route('home'))->with('error', 'You cancelled order processing for product ' . $productTitle);
    }
}
