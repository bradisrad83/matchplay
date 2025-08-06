<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/brad-test', function() {
    $user = auth()->user();
    return $user->getLeagueTeams();
});