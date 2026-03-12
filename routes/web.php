<?php
declare(strict_types=1);

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/application', 'ApplicationController@index');
$router->post('/application', 'ApplicationController@store');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/init-db', 'SetupController@initDb');

// Admin Group
$router->group('/admin', function($router) {
    $router->get('/dashboard', 'Admin\\DashboardController@index')->middleware('admin');
    $router->get('/renters', 'Admin\\RenterController@index')->middleware('admin');
    $router->post('/renters', 'Admin\\RenterController@store')->middleware('admin');
    $router->post('/renters/{id}/update', 'Admin\\RenterController@update')->middleware('admin');
    $router->post('/renters/{id}/delete', 'Admin\\RenterController@delete')->middleware('admin');
    $router->get('/applications', 'Admin\\ApplicationController@index')->middleware('admin');
    $router->post('/applications/{id}/status', 'Admin\\ApplicationController@updateStatus')->middleware('admin');
    $router->post('/applications/{id}/approve', 'Admin\\ApplicationController@approve')->middleware('admin');
    $router->get('/properties', 'Admin\\PropertyController@index')->middleware('admin');
    $router->post('/properties', 'Admin\\PropertyController@store')->middleware('admin');
    $router->post('/properties/{id}/update', 'Admin\\PropertyController@update')->middleware('admin');
    $router->post('/properties/{id}/delete', 'Admin\\PropertyController@delete')->middleware('admin');
    $router->get('/payments', 'Admin\\PaymentController@index')->middleware('admin');
    $router->post('/payments', 'Admin\\PaymentController@store')->middleware('admin');
    $router->post('/payments/{id}/update', 'Admin\\PaymentController@update')->middleware('admin');
    $router->get('/maintenance', 'Admin\\MaintenanceController@index')->middleware('admin');
    $router->post('/maintenance', 'Admin\\MaintenanceController@store')->middleware('admin');
    $router->post('/maintenance/{id}/status', 'Admin\\MaintenanceController@updateStatus')->middleware('admin');
    $router->get('/reports', 'Admin\\ReportController@index')->middleware('admin');
    $router->get('/settings', 'Admin\\SettingController@index')->middleware('admin');
    $router->post('/settings', 'Admin\\SettingController@update')->middleware('admin');
});

// Renter Group
$router->group('/renter', function($router) {
    $router->get('/portal', 'Renter\\PortalController@index')->middleware('renter');
    $router->get('/profile', 'Renter\\ProfileController@index')->middleware('renter');
    $router->post('/profile', 'Renter\\ProfileController@update')->middleware('renter');
    $router->get('/settings', 'Renter\\SettingController@index')->middleware('renter');
    $router->post('/settings', 'Renter\\SettingController@update')->middleware('renter');
    $router->get('/help', 'Renter\\HelpController@index')->middleware('renter');
    $router->post('/help', 'Renter\\HelpController@store')->middleware('renter');
});
