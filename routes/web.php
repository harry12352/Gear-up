<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
| To make routes work with BrowserSync, Virtual Host and Laravel port
*/

$proxy_url = getenv('APP_PROXY_URL');
if (!empty($proxy_url)) {
//    URL::forceRootUrl($proxy_url);
}


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

// Feed
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/top-brands', 'Feed@getBrands')->name('top.brands');

//Feed Routes
Route::middleware('auth')->group(
    function () {
        Route::get('/feed', 'Feed@getProducts')->name('feed');
        Route::get('/popular-followers', 'Feed@getFollowers')->name('popular.followers');
        Route::get('/people-you-may-know', 'Feed@getPeopleToKnow')->name('people.follow');
    }
);

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::post('/follow/{user:username}', 'FollowController@follow')->name('follow.user');
    Route::post('/unfollow/{user:username}', 'FollowController@unFollow')->name('unfollow.user');
});

// Profile Routes
Route::prefix('profile')->group(
    function () {
        // Profile Routes
        Route::get('/{user:username}', 'ProductController@index')->name('profile.index');
        Route::get('/{user:username}/following', 'ProfileController@allFollowing')->name('profile.following');
        Route::get('/{user:username}/followers', 'ProfileController@allFollowers')->name('profile.followers');
        Route::get('/{user:username}/likes', 'ProfileController@allLikes')->name('profile.likes');

        // Product Routes
        Route::get('/{user:username}/products', 'ProductController@index')->name('products.index');
        Route::get('/{user:username}/product/view/{product:slug}', 'ProductController@show')->name('products.show');

        Route::get('/{user:username}/product/buy/{product:slug}/shipping', 'ProductController@orderShipping')->name('product.orderShipping');
        Route::get('/{user:username}/product/buy/{product:slug}/confirm', 'ProductController@orderConfirm')->name('product.orderConfirm');
        Route::get('/{user:username}/product/buy/{product:slug}/payment', 'PaymentController@buy')->name('product.buy');

        Route::get('/{user:username}/product/create', 'ProductController@create')->name('products.create');
        Route::post('/{user:username}/product/store', 'ProductController@store')->name('products.store');
        Route::get('/{user:username}/product/edit/{product:slug}', 'ProductController@edit')->name('products.edit')->middleware('auth');
        Route::post('/{user:username}/product/update/{product:slug}', 'ProductController@update')->name('products.update')->middleware('auth');
        Route::get("/{user:username}/product/delete/{product:slug}", 'ProductController@destroy')->name('products.delete')->middleware('auth');

        //offers Routes
        Route::get('/{user:username}/offers', 'OfferController@showOffers')->name('offers.myoffers')->middleware('auth');
        Route::get('/{user:username}/products/{product}/offers/', 'OfferController@index')->name('offer.index')->middleware('auth');
        Route::post('/{user:username}/products/{product}/offer/new', 'OfferController@newOffer')->name('offer.create')->middleware('auth');
        Route::get('/{user:username}/products/{product}/offer/{offer}/delete', 'OfferController@deleteOffer')->name('offer.delete')->middleware('auth');
        Route::get('/{user:username}/products/{product}/offer/{offer}/accept', 'OfferController@acceptOffer')->name('offer.accept')->middleware('auth');
        Route::get('/{user:username}/products/{product}/offer/{offer}/decline', 'OfferController@declineOffer')->name('offer.decline')->middleware('auth');
        //Messages Routes
        Route::get("/{user:username}/messages", 'MessageController@index')->name('user.messages');
        Route::get("/{user:username}/message/detach/{message}", 'MessageController@detachMessage')->name('user.detach.message')->middleware('auth');
    }
);


// Notification Routes
Route::prefix('notifications')->group(
    function () {
        Route::get('/', 'UserNotificationHandler@getAllNotifications')->name('notifications');
        Route::get('/markReadAll', 'UserNotificationHandler@markAsReadAll')->name('notifications.markAsReadAll');
        Route::get('/{id}/read', 'UserNotificationHandler@markAsRead')->name('notifications.markAsRead');
    }
);


// Settings Routes
Route::group(
    [
        'namespace' => 'Setting',
        'prefix' => 'settings',
    ],
    function () {
        Route::match(['get', 'post'], '/account', 'AccountSettingsController@handleSettings')
            ->name('settings.account');

        Route::match(['get', 'post'], '/security', 'SecuritySettingsController@handleSettings')
            ->name('settings.security');

    Route::get('/delete', 'RemoveUserSettingController@handleSettings')->name('settings.delete');

    Route::post('/delete', 'RemoveUserSettingController@deleteAccount')->name('account.delete');

        Route::match(['get', 'post'], '/shipping', 'ShippingSettingsController@handleSettings')
            ->name('settings.shipping');
    }
);

//Product Images Routes
Route::middleware('auth')->group(
    function () {
        Route::post('/images/store', 'ProductImageController@store')->name('images.store');
        Route::get('/images/delete/{file}', 'ProductImageController@delete')->name('images.delete');
    }
);

//Cookie Routes
Route::get('/add/recently-viewed-product/{id}', 'RecentlyViewedProductsController@push')->name('add.products');
Route::get('/retrieve/recently-viewed-products', 'RecentlyViewedProductsController@pull')->name('retrieve.products');

//Search Routes
Route::get('/search', 'SearchController@search')->name('products.search');

//Product like Routes
Route::middleware('auth')->group(
    function () {
        Route::get('/product/{product}/like', 'LikeController@like')->name('product.like');
        Route::get('/product/{product}/unlike', 'LikeController@unLike')->name('product.unlike');
    }
);

//Product Share Routes
Route::get('/product/{product}/share', 'ShareController@share')->middleware('auth')->name('product.share');


//Comment Routes
Route::middleware('auth')->group(
    function () {
        Route::post('/comment/product/{product}', 'CommentController@storeComment')->name('comment.create');
        Route::get('/comment/{comment}/delete', 'CommentController@delete')->name('comment.delete');
    }
);

//Follow Category Routes
Route::get('/category/{category:slug}', 'FollowCategoryController@showProducts')->name('category.show');
Route::group(
    ['prefix' => 'category', 'middleware' => 'auth'],
    function () {
        Route::get('/{category}/follow', 'FollowCategoryController@follow')->name('category.follow');
        Route::get('/{category}/unfollow', 'FollowCategoryController@unfollow')->name('category.unfollow');
    }
);

//Filter Routes
Route::get('/filter', 'FilterController@getProducts')->name('filter.products');
Route::get('/products', 'FilterController@getAllProducts')->name('products.all');

//Newsletter Route
Route::post('/newsletter', 'NewsLetterController@store');

//Review Routes
Route::middleware('auth')->group(function () {
    Route::post('/product/{product}/review/new', 'ReviewController@storeReview')->name('review.create');
});

//Follow Brand Routes
Route::group(
    ['prefix' => 'brand', 'middleware' => 'auth'],
    function () {
        Route::get('/{brand}/follow', 'FollowBrandController@follow')->name('brand.follow');
        Route::get('/{brand}/unfollow', 'FollowBrandController@unfollow')->name('brand.unfollow');
        Route::get('/{brand:slug}', 'FollowBrandController@showProducts')->name('brand.show');
    }
);
//Pages Routes
Route::get('/page/{page:slug}', 'PagesController@view')->name('page.show');

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/accept-payment', 'PaymentController@accept')->name('payment.accept');
    Route::get('/payment-success', 'PaymentController@success')->name('payment.success');
    Route::get('/payment-failed', 'PaymentController@failed')->name('payment.failed');
    Route::get('/payment-cancel', 'PaymentController@cancel')->name('payment.cancel');
});
