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
// Route untuk menghapus ulasan (Hanya bisa diakses yang sudah login)
$routes->get('/review/delete/(:num)', 'Place::deleteReview/$1', ['filter' => 'auth']);
// Rute untuk pengelolaan ulasan oleh User Sendiri (Wajib Login)
$routes->get('/review/edit/(:num)', 'Place::editReview/$1', ['filter' => 'auth']);
$routes->post('/review/update/(:num)', 'Place::updateReview/$1', ['filter' => 'auth']);
$routes->get('/review/user-delete/(:num)', 'Place::userDeleteReview/$1', ['filter' => 'auth']);
// Rute Halaman Profil
$routes->get('/profile', 'Profile::index', ['filter' => 'auth']);
$routes->get('/profile/edit', 'Profile::edit', ['filter' => 'auth']); 
$routes->post('/profile/update', 'Profile::update', ['filter' => 'auth']);
// CRUD Kategori (Khusus Admin)
$routes->get('/admin/category', 'Category::index', ['filter' => 'auth']);
$routes->post('/admin/category/store', 'Category::store', ['filter' => 'auth']);
$routes->get('/admin/category/delete/(:num)', 'Category::delete/$1', ['filter' => 'auth']);
// Rute ini bisa diakses siapa saja asalkan SUDAH LOGIN
$routes->get('/place/create', 'Place::create', ['filter' => 'auth']);
$routes->post('/place/store', 'Place::store', ['filter' => 'auth']);
$routes->post('/place/searchNominatim', 'Place::searchNominatim', ['filter' => 'auth']);