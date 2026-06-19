<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// 1. RUTE PUBLIK (Bisa Diakses Siapa Saja Tanpa Harus Login)
// ====================================================================
$routes->get('/', 'Home::index');
$routes->get('/tempat/(:num)', 'Place::detail/$1');

// Rute Autentikasi (Login & Register)
$routes->get('/login', 'Auth::login');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Auth::register');
$routes->post('/auth/saveRegister', 'Auth::saveRegister');


// ====================================================================
// 2. RUTE LEVEL USER (Wajib Login / Terfilter 'auth')
// ====================================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Fitur Manajemen Tempat Kuliner oleh User

    $routes->get('/tempat/(:num)', 'Place::detail/$1');
    $routes->get('tambah-kuliner', 'Place::create');
    $routes->post('place/store', 'Place::store');
    $routes->post('place/searchNominatim', 'Place::searchNominatim');
    $routes->get('tempat/edit/(:num)', 'Place::edit/$1');
    $routes->post('tempat/update/(:num)', 'Place::update/$1');
    $routes->get('tempat/delete/(:num)', 'Place::delete/$1');

    // Fitur Ulasan / Review oleh User
    $routes->post('review/store', 'Place::storeReview');
    $routes->get('review/edit/(:num)', 'Place::editReview/$1');
    $routes->post('review/update/(:num)', 'Place::updateReview/$1');
    $routes->get('review/delete/(:num)', 'Place::deleteReview/$1');
    $routes->get('review/user-delete/(:num)', 'Place::userDeleteReview/$1');

    // Fitur Profil Pengguna
    $routes->get('profile', 'Profile::index');
    $routes->get('profile/edit', 'Profile::edit');
    $routes->post('profile/update', 'Profile::update');
});


// ====================================================================
// 3. RUTE LEVEL ADMIN (Wajib Login & Masuk Kelompok 'admin')
// ====================================================================
$routes->group('admin', ['filter' => 'auth'], function ($routes) {

    // Mengarahkan URL admin/places ke Controller Place dan fungsi places
    $routes->get('places', 'Place::places');

    // Kelola Master Kategori Kuliner (Sinkron dengan CategoryController)
    $routes->get('/tempat/(:num)', 'Place::detail/$1');
    $routes->get('categories', 'CategoryController::index');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->post('categories/update/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');

    // Kelola Master Tag Karakteristik (Sinkron dengan TagController)
    $routes->get('tags', 'TagController::index');
    $routes->post('tags/store', 'TagController::store');
    $routes->post('tags/update/(:num)', 'TagController::update/$1');
    $routes->get('tags/delete/(:num)', 'TagController::delete/$1');
});

// ====================================================================
// 4. RUTE WEBSERVICE API (Untuk dikonsumsi aplikasi luar / Mobile)
// ====================================================================

$routes->get('api/docs', 'Api\PlaceApi::docs');
// PERBAIKAN: Menambahkan filter 'apikey' agar rute ini dikunci
$routes->group('api', ['filter' => 'apikey'], function ($routes) {
    // Endpoint GET untuk mengambil semua data tempat
    $routes->get('places', 'Api\PlaceApi::index');
});