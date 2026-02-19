<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --------------------------------------------------------------------
// Default Route
// --------------------------------------------------------------------
$routes->get('/', 'Dashboard\DashboardController::index');

// --------------------------------------------------------------------
// Dashboard Routes (AJAX Pages)
// --------------------------------------------------------------------
$routes->group('dashboard', ['namespace' => 'App\Controllers\Dashboard'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('loadPage/(:segment)', 'DashboardController::loadPage/$1');
    $routes->get('getData', 'DashboardController::getData');
});

// --------------------------------------------------------------------
// Perincian Modul
// --------------------------------------------------------------------
$routes->group('perincianmodul', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'PerincianModulController::index');
    $routes->get('getServis/(:num)', 'PerincianModulController::getServis/$1');
    $routes->post('save', 'PerincianModulController::save');
    $routes->get('delete/(:num)', 'PerincianModulController::delete/$1');
});

// --------------------------------------------------------------------
// Tambahan Perincian Modul
// --------------------------------------------------------------------
$routes->group('dashboard', ['namespace' => 'App\Controllers'], function ($routes) {
    // MAIN PAGE
    $routes->get('TambahanPerincian', 'TambahanPerincianController::index');

    // AJAX ROUTES
    $routes->get('TambahanPerincian/getServis/(:num)', 'TambahanPerincianController::getServis/$1');
    $routes->post('TambahanPerincian/saveServis', 'TambahanPerincianController::saveServis');
    $routes->post('TambahanPerincian/deleteServis', 'TambahanPerincianController::deleteServis');
    $routes->get('TambahanPerincian/getAll', 'TambahanPerincianController::getAll');
});

// --------------------------------------------------------------------
// Dokumen Management
$routes->group('dokumen', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'DokumenController::index');
    $routes->get('getDokumen/(:num)', 'DokumenController::getDokumen/$1');
    $routes->post('tambah', 'DokumenController::tambah');
    $routes->get('edit/(:num)', 'DokumenController::edit/$1');
    $routes->post('kemaskini/(:num)', 'DokumenController::kemaskini/$1');
    $routes->post('hapus/(:num)', 'DokumenController::hapus/$1');
    $routes->get('viewFile/(:num)/(:any)', 'DokumenController::viewFile/$1/$2');
});

// --------------------------------------------------------------------
// Approval Dokumen Management
// --------------------------------------------------------------------
$routes->group('approvaldokumen', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'ApprovalDokumenController::index');
    $routes->get('getAll', 'ApprovalDokumenController::getAll');
    $routes->get('getDokumen/(:num)', 'ApprovalDokumenController::getDokumen/$1');
    $routes->post('changeStatus/(:num)/(:any)', 'ApprovalDokumenController::changeStatus/$1/$2');
    $routes->get('viewFile/(:num)/(:any)', 'ApprovalDokumenController::viewFile/$1/$2');
});

// --------------------------------------------------------------------
// User Management
// --------------------------------------------------------------------
$routes->group('users', ['namespace' => 'App\Controllers'], function($routes){
    $routes->get('/', 'UserController::index');
    $routes->get('getAll', 'UserController::getAll');
    $routes->get('(:num)', 'UserController::show/$1');
    $routes->post('add', 'UserController::add');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->post('delete/(:num)', 'UserController::delete/$1');
});

// --------------------------------------------------------------------
// Authentication & Profile Management
// --------------------------------------------------------------------
$routes->get('register', '\App\Controllers\Auth::register');
$routes->post('register', '\App\Controllers\Auth::attemptRegister');
$routes->get('login', '\App\Controllers\Auth::login');
$routes->post('login', '\App\Controllers\Auth::attemptLogin');
$routes->get('logout', '\App\Controllers\Auth::logout');

$routes->get('profile', '\App\Controllers\Auth::profile');
$routes->post('profile/update', '\App\Controllers\Auth::updateProfile');
$routes->post('profile/update-password', '\App\Controllers\Auth::updatePassword');
$routes->get('profile/delete-pic', '\App\Controllers\Auth::deleteProfilePic');

// --- Direct Password Reset (Tiada Emel) ---
$routes->get('forgot-password', '\App\Controllers\Auth::forgotPassword');
$routes->post('forgot-password', '\App\Controllers\Auth::attemptDirectReset');

// --- Forgot Password (3 Step) ---
$routes->group('forgot', ['namespace' => 'App\Controllers'], function($routes)
{
    //Step 1: Masukkan email
    $routes->get('step1', 'Auth::forgotStep1');
    $routes->post('step1', 'Auth::processStep1');

    //Step 2: Hantar or sahkan kod
    $routes->get('step2', 'Auth::forgotStep2');
    $routes->post('step2', 'Auth::processStep2');

    //Step 3: Reset password
    $routes->get('step3', 'Auth::forgotStep3');
    $routes->post('step3', 'Auth::processStep3');
});

// --------------------------------------------------------------------
// Servis Kelulusan
// --------------------------------------------------------------------
$routes->group('serviskelulusan', ['namespace' => 'App\Controllers\Servis'], function($routes){
    $routes->get('/', 'ServisKelulusanController::index');
    $routes->get('getAll', 'ServisKelulusanController::getAll');
    $routes->get('getServis/(:num)', 'ServisKelulusanController::getServis/$1');
    $routes->post('changeStatus/(:num)/(:segment)', 'ServisKelulusanController::changeStatus/$1/$2');
});

// --------------------------------------------------------------------
// Frontend
// --------------------------------------------------------------------
$routes->group('', ['namespace' => 'App\Controllers\Frontend'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    $routes->group('perincian', function($routes) {
        $routes->get('/', 'PerincianController::index');
        $routes->get('getServis/(:num)', 'PerincianController::getServis/$1');
        $routes->post('save', 'PerincianController::save');
    });

    $routes->group('pengurusan', function($routes) {
        $routes->get('/', 'DokumenPengurusanController::index');
        $routes->get('getDokumen/(:num)', 'DokumenPengurusanController::getDokumen/$1');
        $routes->get('getDokumenById/(:num)', 'DokumenPengurusanController::getDokumenById/$1');
        $routes->post('tambah', 'DokumenPengurusanController::tambah');
        $routes->post('kemaskini/(:num)', 'DokumenPengurusanController::kemaskini/$1');
        $routes->get('remove/(:num)', 'DokumenPengurusanController::remove/$1');
    });
});

// --------------------------------------------------------------------
// FAQ Management
// --------------------------------------------------------------------
$routes->group('faq', function($routes){
    $routes->get('', 'FaqController::index');
    $routes->get('(:num)', 'FaqController::index/$1');
    $routes->get('create/(:num)', 'FaqController::create/$1');
    $routes->post('store', 'FaqController::store');
    $routes->get('edit/(:num)', 'FaqController::edit/$1');
    $routes->post('update/(:num)', 'FaqController::update/$1');
    $routes->delete('delete/(:num)', 'FaqController::delete/$1');
    $routes->get('ajax/(:num)', 'FaqController::ajax/$1');
});