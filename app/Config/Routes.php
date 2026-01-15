<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================================================================
// 1. HALAMAN PUBLIK
// =======================================================================
$routes->get('/', 'Home::index');


// =======================================================================
// 2. OTENTIKASI (LOGIN & REGISTER)
// =======================================================================
$routes->get('/register', 'AuthController::register');
$routes->post('/register/process', 'AuthController::processRegister');

$routes->get('/login', 'AuthController::login');
$routes->post('/login/process', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');


// =======================================================================
// 3. DASHBOARD
// =======================================================================
$routes->get('/dashboard', 'DashboardController::index');
$routes->post('dashboard/update_availability', 'DashboardController::update_availability');

// =======================================================================
// 4. FITUR KONSULTASI (FASE 1 - 5)
// =======================================================================
$routes->group('konsultasi', function ($routes) {
    // Fase 1: Pengajuan
    $routes->get('ajukan', 'KonsultasiController::ajukan');
    $routes->post('process', 'KonsultasiController::processAjukan');

    // Fase 2: Verifikasi Sekretaris & Lawyer
    $routes->get('verifikasi/(:num)', 'KonsultasiController::verifikasi/$1');
    $routes->post('process-verifikasi', 'KonsultasiController::processVerifikasi');
    $routes->post('lawyer-response', 'KonsultasiController::lawyerResponse');

    // Fase 3: Pembayaran
    $routes->get('pembayaran/(:num)', 'KonsultasiController::pembayaran/$1');
    $routes->get('finish-payment', 'KonsultasiController::finishPayment');

    // Fase 5: Pelaksanaan & Selesai
    $routes->get('tiket/(:num)', 'KonsultasiController::tiket/$1');

    // --- PERBAIKAN DI SINI (Gunakan GET dan parameter NUM) ---
    $routes->get('selesai/(:num)', 'KonsultasiController::selesai/$1');
});


// =======================================================================
// 5. MANAJEMEN KASUS (FASE 6)
// =======================================================================
$routes->group('kasus', function ($routes) {
    $routes->get('/', 'KasusController::index');              // List Kasus
    $routes->get('update/(:num)', 'KasusController::update/$1'); // Form Update
    $routes->post('process', 'KasusController::processUpdate');  // Simpan
    $routes->get('timeline/(:num)', 'KasusController::timeline/$1'); // Klien: Lihat Progres
});


// =======================================================================
// 6. LAPORAN & KEUANGAN (SEKRETARIS)
// =======================================================================
$routes->group('laporan', function ($routes) {
    $routes->get('/', 'LaporanController::index');
    $routes->get('cetak', 'LaporanController::cetak');
    $routes->get('keuangan', 'LaporanController::keuangan'); // Fitur Keuangan
});


// =======================================================================
// 7. MANAJEMEN USER (SEKRETARIS)
// =======================================================================
$routes->group('users', function ($routes) {
    $routes->get('/', 'UsersController::index');              // List User
    $routes->get('form', 'UsersController::form');            // Form Tambah
    $routes->get('form/(:num)', 'UsersController::form/$1');  // Form Edit
    $routes->post('save', 'UsersController::save');           // Simpan
    $routes->get('delete/(:num)', 'UsersController::delete/$1'); // Hapus
});
