<?php

use Illuminate\Support\Facades\Route;

Route::name('shortener.')->group(function() {
    Route::get('/','ShortLinkController@index')->name('index');
    Route::get('{code}', 'ShortLinkController@redirectShortLink')->name('get.link');
    Route::post('/shorten-link', 'ShortLinkController@store')->name('store.link');
});
