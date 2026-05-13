<?php

namespace Config;

$routes = \Config\Services::routes();

// Routes publiques
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

// Routes protégées (authentification requise)
$routes->group('employe', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'EmployeController::dashboard');
    $routes->get('create', 'EmployeController::create');
    $routes->post('store', 'EmployeController::store');
    $routes->get('mes-demandes', 'EmployeController::index');
    $routes->post('annuler/(:num)', 'EmployeController::annuler/$1');
    $routes->get('profil', 'EmployeController::profil');
});