<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---- AUTH ----
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);

// ---- PUBLIC ----
$routes->get('/etudiants', 'Etudiants::index');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// ---- EMPLOYÉ ----
$routes->group('employee', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Employee\EmployeeController::dashboard');
    $routes->get('dashboard', 'Employee\EmployeeController::dashboard');
    $routes->get('demande/create', 'Employee\EmployeeController::createDemande');
    $routes->post('demande/store', 'Employee\EmployeeController::storeDemande');
    $routes->get('mes-demandes', 'Employee\EmployeeController::mesDemandes');
    $routes->get('demande/(:num)/annuler', 'Employee\EmployeeController::annulerDemande/$1');
    $routes->get('profil', 'Employee\EmployeeController::profil');
});

// ---- RH ----
$routes->group('rh', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RH\RHController::demandesEnAttente');
    $routes->get('demandes', 'RH\RHController::demandesEnAttente');
    $routes->post('approuver/(:num)', 'RH\RHController::approuver/$1');
    $routes->post('refuser/(:num)', 'RH\RHController::refuser/$1');
    $routes->get('filtres', 'RH\RHController::filtres');
    $routes->get('soldes-equipe', 'RH\RHController::soldesEquipe');
});

// ---- ADMIN (protégées par auth + rôle admin) ----
$routes->group('admin', ['filter' => 'auth,role:admin'], function ($routes) {
    // Dashboard admin
    $routes->get('/', 'Admin\AdminStatsController::index');
    $routes->get('dashboard', 'Admin\AdminStatsController::index');

    // Employés
    $routes->get('employes', 'Admin\AdminEmployesController::index');
    $routes->get('employes/create', 'Admin\AdminEmployesController::create');
    $routes->post('employes/store', 'Admin\AdminEmployesController::store');
    $routes->get('employes/(:num)/edit', 'Admin\AdminEmployesController::edit/$1');
    $routes->post('employes/(:num)/update', 'Admin\AdminEmployesController::update/$1');
    $routes->get('employes/(:num)/confirm-delete', 'Admin\AdminEmployesController::confirmDelete/$1');
    $routes->post('employes/(:num)/delete', 'Admin\AdminEmployesController::delete/$1');

    // Départements
    $routes->get('departements', 'Admin\AdminDepartementsController::index');
    $routes->get('departements/create', 'Admin\AdminDepartementsController::create');
    $routes->post('departements/store', 'Admin\AdminDepartementsController::store');
    $routes->get('departements/(:num)/edit', 'Admin\AdminDepartementsController::edit/$1');
    $routes->post('departements/(:num)/update', 'Admin\AdminDepartementsController::update/$1');
    $routes->get('departements/(:num)/confirm-delete', 'Admin\AdminDepartementsController::confirmDelete/$1');
    $routes->post('departements/(:num)/delete', 'Admin\AdminDepartementsController::delete/$1');

    // Types de congé
    $routes->get('types-conge', 'Admin\AdminTypesCongeController::index');
    $routes->get('types-conge/create', 'Admin\AdminTypesCongeController::create');
    $routes->post('types-conge/store', 'Admin\AdminTypesCongeController::store');
    $routes->get('types-conge/(:num)/edit', 'Admin\AdminTypesCongeController::edit/$1');
    $routes->post('types-conge/(:num)/update', 'Admin\AdminTypesCongeController::update/$1');
    $routes->get('types-conge/(:num)/confirm-delete', 'Admin\AdminTypesCongeController::confirmDelete/$1');
    $routes->post('types-conge/(:num)/delete', 'Admin\AdminTypesCongeController::delete/$1');

    // Soldes
    $routes->get('soldes', 'Admin\AdminSoldesController::index');
    $routes->get('soldes/create', 'Admin\AdminSoldesController::create');
    $routes->post('soldes/store', 'Admin\AdminSoldesController::store');
    $routes->get('soldes/(:num)/edit', 'Admin\AdminSoldesController::edit/$1');
    $routes->post('soldes/(:num)/update', 'Admin\AdminSoldesController::update/$1');
    $routes->get('soldes/(:num)/confirm-delete', 'Admin\AdminSoldesController::confirmDelete/$1');
    $routes->post('soldes/(:num)/delete', 'Admin\AdminSoldesController::delete/$1');

    // Statistiques
    $routes->get('stats', 'Admin\AdminStatsController::index');
});
