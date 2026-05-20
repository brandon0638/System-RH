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
    $routes->post('changer-mot-de-passe', 'EmployeController::changerMotDePasse');
    $routes->post('modifier-nom', 'EmployeController::modifierNom'); 
});

// Routes RH (accès: rh et admin)
$routes->group('rh', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'RhController::dashboard');
    $routes->get('demandes', 'RhController::index');
    $routes->post('approuver/(:num)', 'RhController::approuver/$1');
    $routes->post('refuser/(:num)', 'RhController::refuser/$1');
    $routes->get('historique', 'RhController::historique');
    $routes->get('soldes', 'RhController::soldes');
});

$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    // 1. Le Dashboard
    $routes->get('dashboard', 'Admin\AdminController::dashboard');

    // 2. Gestion des Employés
    $routes->get('employes', 'Admin\AdminController::listeEmployes');
    $routes->get('employes/nouveau', 'Admin\AdminController::formEmploye'); // Formulaire ajout
    $routes->post('employes/enregistrer', 'Admin\AdminController::saveEmploye'); // Action enregistrer
    $routes->get('employes/statut/(:num)', 'Admin\AdminController::toggleStatut/$1'); // Activer/Désactiver
    // 3. Types de congés
    $routes->get('types', 'Admin\AdminController::listeTypes');
});