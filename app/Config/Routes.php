<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/place/searchNominatim', 'Place::searchNominatim');
$routes->post('/review/store', 'Place::storeReview');
$routes->get('/tempat/(:num)', 'Place::detail/$1');
// Routes Login & Logout
$routes->get('/login', 'Auth::login');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/logout', 'Auth::logout');
// Lindungi fitur tambah data dengan filter 'auth'
$routes->get('/tambah-kuliner', 'Place::create', ['filter' => 'auth']);
$routes->post('/place/store', 'Place::store', ['filter' => 'auth']);
// Route untuk hapus tempat 
$routes->get('/tempat/delete/(:num)', 'Place::delete/$1', ['filter' => 'auth']);
// Route untuk Edit Data
$routes->get('/tempat/edit/(:num)', 'Place::edit/$1', ['filter' => 'auth']);
$routes->post('/tempat/update/(:num)', 'Place::update/$1', ['filter' => 'auth']);
$routes->get('/register', 'Auth::register');
$routes->post('/auth/saveRegister', 'Auth::saveRegister');