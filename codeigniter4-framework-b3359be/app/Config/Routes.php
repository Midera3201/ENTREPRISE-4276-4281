<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/etudiants', 'Etudiants::index');

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('admin', 'Dashboard::admin', ['filter' => 'auth,role:admin']);
$routes->get('rh', 'Dashboard::rh', ['filter' => 'auth,role:rh']);
$routes->get('employe', 'Dashboard::employe', ['filter' => 'auth,role:employe']);
