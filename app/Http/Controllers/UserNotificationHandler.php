<?php

namespace App\Http\Controllers;

use App\Notifications\AcceptedProductOffer;
use App\Notifications\Comment;
use App\Notifications\DeclinedProductOffer;
use App\Notifications\LikeProduct;
use App\Notifications\LikeProductFollower;
use App\Notifications\NewFollower;
use App\Notifications\ProductOffer;
use App\Notifications\NewProductFollower;
use App\Notifications\ShareProduct;
use App\Notifications\ShareProductFollower;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserNotificationHandler extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllNotifications()
    {
        $user = Auth::User();

        $allNotifications = array();
        $notifications = $user->notifications()->paginate(5);
        $allNotifications['lastPage'] = $notifications->lastPage();
        foreach ($notifications as $index => $notification) {
            $userNotification = array();

            $userNotification['id'] = $notification->id;
            $userNotification['time'] = $notification->created_at->diffForHumans();
            $userNotification['read'] = ($notification->read_at) ? true : false;
            $userNotification['markAsReadURL'] = route('notifications.markAsRead', ['id' => $notification->id]);

            if ($notification->type === NewFollower::class) {
                $followerUserData = User::find($notification->data['followerUserID']);
                if (!$followerUserData) {
                    continue;
                }

                $followerName = $followerUserData->first_name . ' ' . $followerUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $followerName . "</span> is now started following you.";

                if (isset($followerUserData->profileImage)) {
                    $userNotification['image_url'] = asset('storage/' . $followerUserData->profileImage->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = route('profile.index', ['user' => $followerUserData->username]);
            }
            if ($notification->type === LikeProductFollower::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> liked a product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type === LikeProduct::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . '</span> liked your product <b>' . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type === ShareProductFollower::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> shared a product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type == ShareProduct::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> shared your product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }

            if ($notification->type == NewProductFollower::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> has uploaded a new product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type == ProductOffer::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> has made offer on your product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }

            if ($notification->type == Comment::class) {
                $productData = Product::find($notification->data['product_id']);
                $productUserData = User::find($notification->data['product_user_id']);
                $productOwnerUsername = User::find($productData->user_id)->username;
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwnerUsername]);
                $productUserName = $productUserData->first_name . ' ' . $productUserData->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productUserName . "</span> has commented on your product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type == AcceptedProductOffer::class) {
                $productData = Product::find($notification->data['product_id']);
                $productOwner = User::find($productData->user_id);
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwner->username]);
                $productOwnerName = $productOwner->first_name . ' ' . $productOwner->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productOwnerName . "</span> has accepted your offer on the product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            if ($notification->type == DeclinedProductOffer::class) {
                $productData = Product::find($notification->data['product_id']);
                $productOwner = User::find($productData->user_id);
                if (!$productData) {
                    continue;
                }
                $productName = $productData->title;
                $productLink = route('products.show', ['id' => $productData->id, 'user' => $productOwner->username]);
                $productOwnerName = $productOwner->first_name . ' ' . $productOwner->last_name;
                $userNotification['message'] = "<span class='notification--user'>" . $productOwnerName . "</span> has declined your offer on the product <b>" . $productName . "</b>";

                if (isset($productData->files) && count($productData->files) > 0) {
                    $userNotification['image_url'] = asset('storage/' . $productData->files[0]->path);
                } else {
                    $userNotification['image_url'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==';
                }

                $userNotification['url'] = $productLink;
            }
            $allNotifications['notifications'][] = $userNotification;
        }
        return response()->json($allNotifications, 200);
    }

    public function markAsReadAll()
    {
        $user = Auth::User();

        $user->unreadNotifications->markAsRead();
        return response()->json([], 200);
    }

    public function markAsRead($id)
    {
        $currentNotificaion = DB::table('notifications')->where('id', $id)->exists();
        if ($currentNotificaion) {
            DB::table('notifications')->where('id', $id)->update(['read_at' => Carbon::now()]);
            return response()->json(['error' => false, 'message' => 'Notification marked as read'], 200);
        }
        return response()->json(['error' => true, 'message' => 'Notification not found'], 200);
    }
}
