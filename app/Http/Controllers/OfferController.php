<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index(User $user, Product $product)
    {
        $productOffers = $product->offers()->paginate(12);
        if (self::offerOwner($user) == true && self::productOwner($user, $product) == true) {
            return view('profile.products.offers.index', ['user' => $user, 'product' => $product, 'offers' => $productOffers]);
        }
        return redirect()->back()->with('error', 'You are not the owner of this product');

    }

    public function newOffer(User $user, Product $product, Request $request)
    {
        $request->validate([
            'offered_price' => 'required|numeric'
        ]);
        $productOwner = $product->user;
        $user_offer = Offer::where('user_id', $user['id'])->where('product_id', $product->id)->first();
        if ($user_offer && $user_offer['status'] === 'pending') {
            return redirect()->back()->with('error', 'Sorry, you already placed your offer on this product');
        }
        if ($request['offered_price'] > $product->price) {
            return redirect()->back()->with('error', 'Your offer price cannot be greater than product listing price!');
        }
        if (Auth::id() !== $productOwner['id']) {
            $offer = Offer::create([
                'user_id' => $user['id'],
                'product_id' => $product['id'],
                'offered_price' => $request['offered_price'],
                'status' => 'pending'
            ]);
            NotificationController::newProductOfferNotification($productOwner, $offer);
            return redirect()->back()->with('success', 'Your offer has been submitted successfully to seller');
        }
        return redirect()->back()->with('error', 'Sorry, you cannot place offer on your own product');
    }

    public function deleteOffer(User $user, Product $product, Offer $offer)
    {
        if (self::offerOwner($user)) {
            if ($offer['status'] == 'pending') {
                if ($offer['user_id'] == $user['id'] && $offer['product_id'] == $product['id']) {
                    $offer->delete();
                    return response()->json(['error' => false, 'message' => 'You have successfully deleted offer on this product'], 200);
                }
                return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
            }
            return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
        }

        return response()->json(['error' => true, 'message' => 'You have not access to perform this action'], 401);
    }

    public function acceptOffer(User $user, Product $product, Offer $offer)
    {
        if (self::offerOwner($user) && self::productOwner($user, $product) == true) {
            $offerUserName = $offer->user->first_name . ' ' . $offer->user->last_name;
            $offer['status'] = 'accepted';
            $offer->save();
            NotificationController::productOfferAcceptNotification(User::find($offer['user_id']), $offer);
            // TODO: start payment and shipping process here because we not stopping only after message - Modify notification message too
            return redirect()->back()->with('success', "You have accepted {$offerUserName}'s offer on this product");
        }
        return redirect()->back()->with('error', 'You do not have access to perform this action');
    }

    public function declineOffer(User $user, Product $product, Offer $offer)
    {
        if (self::offerOwner($user) && self::productOwner($user, $product) == true) {
            $offerUserName = $offer->user->first_name . ' ' . $offer->user->last_name;
            $offer['status'] = 'declined';
            $offer->save();

            // We not informing user who placed the offer
            NotificationController::productOfferDeclinetNotification(User::find($offer['user_id']), $offer);
            return redirect()->back()->with('success', "You have declined {$offerUserName}'s offer on this product");
        }
        return redirect()->back()->with('error', 'You do not have access to perform this action');
    }

    public function showOffers(User $user)
    {
        if (self::offerOwner($user) == true) {
            $userOffers = $user->offers()->paginate(12);
            return view('profile.offers.index', ['userOffers' => $userOffers, 'user' => $user]);
        }
        return redirect()->back()->with('error', 'You do not have access to perform this action');
    }

    public static function offerOwner(User $user)
    {
        return Auth::user() && Auth::id() == $user['id'];
    }

    public static function productOwner(User $user, Product $product)
    {
        return $user['id'] == $product['user_id'];
    }
}
