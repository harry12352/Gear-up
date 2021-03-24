<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Notifications\AcceptedProductOffer;
use App\Notifications\Comment;
use App\Notifications\DeclinedProductOffer;
use App\Notifications\LikeProduct;
use App\Notifications\LikeProductFollower;
use App\Notifications\NewFollower;
use App\Notifications\NewProductFollower;
use App\Notifications\ProductOffer;
use App\Notifications\ShareProduct;
use App\Notifications\ShareProductFollower;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Share;
use App\Models\User;

class NotificationController
{
    public static function newFollowerNotification($followerUser, $followedUser)
    {
        $followedUser->notify(new NewFollower($followerUser));
    }

    // sending notification to all the followers of the person who liked the product
    public static function LikedProductFollowerNotification(User $notifyableUser, Like $likedInstance)
    {
        $notifyableUser->notify(new LikeProductFollower($likedInstance));
    }

    // sending notification to product owner
    public static function newLikedProductNotification(User $productOwner, Like $likedInstance)
    {
        $productOwner->notify(new LikeProduct($likedInstance));
    }

    // sending notification to all the followers of the person who liked the product
    public static function SharedProductFollowerNotification(User $notifyableUser, Share $shareInstance)
    {
        $notifyableUser->notify(new ShareProductFollower($shareInstance));
    }

    // sending notification to product owner
    public static function newSharedProductNotification(User $productOwner, Share $shareInstance)
    {
        $productOwner->notify(new ShareProduct($shareInstance));
    }

    public static function newProductFollowerNotification(User $notifyableUser, Product $productInstance)
    {
        $notifyableUser->notify(new NewProductFollower($productInstance));
    }

    public static function newCommentNotification(User $notifyableUser, \App\Models\Comment $commentInstance)
    {
        $notifyableUser->notify(new Comment($commentInstance));
    }

    public static function newProductOfferNotification(User $productOwner, Offer $offerInstance)
    {
        $productOwner->notify(new ProductOffer($offerInstance));
    }

    public static function productOfferAcceptNotification(User $notifyableUser, Offer $offerInstance)
    {
        $notifyableUser->notify(new AcceptedProductOffer($offerInstance));
    }

    public static function productOfferDeclinetNotification(User $notifyableUser, Offer $offerInstance)
    {
        $notifyableUser->notify(new DeclinedProductOffer($offerInstance));
    }

}
