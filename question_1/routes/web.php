<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('docs', function () { return view('docs'); });

$router->get('cleaners', 'CleanerController@index');
$router->post('cleaners', 'CleanerController@create');

$router->post('cleaners/{id}/bookings', 'BookingController@create');
$router->get('cleaners/{id}/bookings', 'BookingController@index');
$router->delete('cleaners/{cleaner_id}/bookings/{booking_id}', 'BookingController@destroy');
$router->patch('cleaners/{cleaner_id}/bookings/{booking_id}', 'BookingController@update');
$router->get('cleaners/available/{date}', 'AvailableController@index');

$router->get('companies', 'CompanyController@index');
$router->post('companies', 'CompanyController@create');
