<?php
/**
 * Created by PhpStorm.
 * User: kuehn_000
 * Date: 26.06.2018
 * Time: 14:12
 */



Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::auth();
    Route::get('/', 'HomeController@index') ;
    Route::get('/reviews', 'HomeController@reviews') ;
    Route::get('/login', 'HomeController@login') ;
    Route::get('/faq', 'HomeController@faq') ;
    Route::post('/login-review', 'LoginController@login') ;
    Route::any('/password/reset22222', 'LoginController@reset') ;
    Route::any('/beta-register', 'LoginController@betaRegister') ;
    Route::any('/register', 'LoginController@betaRegister') ;
    Route::any('/beta-register-save', 'LoginController@betaRegisterSave') ;
    Route::get('/home', 'HomeController@login') ;


    Route::get('login', 'HomeController@login') ;


    Route::get('/marketplace', 'HomeController@marketplace') ;
    Route::get('/marketplace/categories', 'HomeController@marketplaceCats') ;
    Route::get('/marketplace/detail', 'HomeController@marketplaceDetail') ;
    Route::get('/marketplace', 'HomeController@marketplace') ;
    Route::get('/logout', 'HomeController@logout');

    Route::get('/open-rating', 'Frontend\RatingController@open');
    Route::post('/store-rating', 'Frontend\RatingController@store');
    Route::any('/create-rating', 'Frontend\RatingController@create');
    Route::any('/save-rating-template', 'Frontend\RatingController@save');
    Route::post('/additionally-infos', 'Frontend\RatingController@add');
    Route::any('/invite-rating', 'Frontend\RatingController@invite');
    Route::any('/overview', 'Frontend\RatingController@getOverview');
    Route::any('/newsletter-sign-up', 'HomeController@newsletter');
    Route::any('/send-message', 'HomeController@contact');
    Route::any('/terms', 'HomeController@terms');
    Route::any('/about', 'HomeController@about');
    Route::any('/terms-detail', 'HomeController@termsDetail');
    Route::any('/privacy', 'HomeController@privacy');



});


