<?php

namespace Config;

$routes = Services::routes();

$routes->setAutoRoute(false);

// Routes publiques
$routes->get('/', 'HomeController::index');
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'UserController::register');
$routes->post('/registerTreatment', 'UserController::registerPost');
$routes->get('/register/health', 'UserController::registerHealth');
$routes->post('/register/health', 'UserController::registerHealthPost');
$routes->get('/hero', 'HomeController::hero');
$routes->get('/regime/perdre', 'RegimeController::perdre');
$routes->get('/regime/gagner', 'RegimeController::gagner');
$routes->get('/regime/imc', 'RegimeController::imc');
$routes->post('/regime/perdre/pdf', 'RegimeController::exportPerdrePdf');
$routes->post('/regime/gagner/pdf', 'RegimeController::exportGagnerPdf');
$routes->post('/options/acheter', 'OptionController::purchase');
$routes->get('/code/redeem', 'CodeController::redeemForm');
$routes->post('/code/redeem', 'CodeController::redeem');

// Back Office Admin
$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('dashboard/stats', 'Admin\DashboardController::getStats');
    
    // CRUD Régimes
    $routes->get('regimes', 'Admin\RegimeController::index');
    $routes->get('regimes/create', 'Admin\RegimeController::create');
    $routes->post('regimes/store', 'Admin\RegimeController::store');
    $routes->get('regimes/edit/(:num)', 'Admin\RegimeController::edit/$1');
    $routes->post('regimes/update/(:num)', 'Admin\RegimeController::update/$1');
    $routes->get('regimes/delete/(:num)', 'Admin\RegimeController::delete/$1');
    
    // CRUD Sports
    $routes->get('sports', 'Admin\SportController::index');
    $routes->get('sports/create', 'Admin\SportController::create');
    $routes->post('sports/store', 'Admin\SportController::store');
    $routes->get('sports/edit/(:num)', 'Admin\SportController::edit/$1');
    $routes->post('sports/update/(:num)', 'Admin\SportController::update/$1');
    $routes->get('sports/delete/(:num)', 'Admin\SportController::delete/$1');
    
    // CRUD Codes
    $routes->get('codes', 'Admin\CodeController::index');
    $routes->get('codes/create', 'Admin\CodeController::create');
    $routes->post('codes/store', 'Admin\CodeController::store');
    $routes->get('codes/edit/(:num)', 'Admin\CodeController::edit/$1');
    $routes->post('codes/update/(:num)', 'Admin\CodeController::update/$1');
    $routes->get('codes/delete/(:num)', 'Admin\CodeController::delete/$1');
    
    // CRUD Paramètres
    $routes->get('parametres', 'Admin\ParametreController::index');
    $routes->get('parametres/edit/(:num)', 'Admin\ParametreController::edit/$1');
    $routes->post('parametres/update/(:num)', 'Admin\ParametreController::update/$1');
});
