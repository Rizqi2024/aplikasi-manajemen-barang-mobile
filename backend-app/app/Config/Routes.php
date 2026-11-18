<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Web Admin Dashboard
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('admin', 'Dashboard::index');

// Web Admin - Modul Barang
$routes->get('barang', 'Barang::index');
$routes->get('admin/barang', 'Barang::index');
$routes->post('barang/simpan', 'Barang::store');
$routes->post('barang/update/(:num)', 'Barang::update/$1');
$routes->get('barang/hapus/(:num)', 'Barang::delete/$1');

// Web Admin - Modul Supplier
$routes->get('supplier', 'Supplier::index');
$routes->get('admin/supplier', 'Supplier::index');
$routes->post('supplier/simpan', 'Supplier::store');
$routes->post('supplier/update/(:num)', 'Supplier::update/$1');
$routes->get('supplier/hapus/(:num)', 'Supplier::delete/$1');

// API Android & Eksternal
$routes->group('api', static function ($routes) {
    // API Login (public)
    $routes->post('login', 'Api\Auth::login');

    // Barang routes (public)
    $routes->get('barang', 'Api\Barang::index');
    $routes->get('barang/(:num)', 'Api\Barang::show/$1');
    $routes->post('barang', 'Api\Barang::create');
    $routes->put('barang/(:num)', 'Api\Barang::update/$1');
    $routes->delete('barang/(:num)', 'Api\Barang::delete/$1');

    // Protected routes — require valid API token
    $routes->group('', ['filter' => 'authApi'], static function ($routes) {
        $routes->get('supplier', 'Api\Supplier::index');
    });
});
