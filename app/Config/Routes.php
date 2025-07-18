<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Front_controller::index');

$routes->get('Login', 'Login_controller::index');
$routes->post('Login-Penyewa', 'Login_controller::login_penyewa');
$routes->post('Registrasi', 'Front_controller::register');
$routes->post('Login-proses', 'Login_controller::login');
$routes->get('Logout', 'Login_controller::logout');
$routes->get('Logout-Penyewa', 'Login_controller::logout_penyewa');

$routes->get('Dashboard', 'Dashboard::index');

$routes->get('Kamar', 'Kamar_controller::index');
$routes->post('save_kamar', 'Kamar_controller::save');
$routes->post('edit_kamar', 'Kamar_controller::edit');
$routes->post('delete_kamar', 'Kamar_controller::delete');

$routes->get('Penyewa', 'Penyewa_controller::index');
$routes->post('save_penyewa', 'Penyewa_controller::save');
$routes->post('edit_penyewa', 'Penyewa_controller::edit');
$routes->post('delete_penyewa', 'Penyewa_controller::delete');

$routes->get('Sewa-Kamar', 'Sewa_kamar_controller::index');
$routes->post('save-sewa-kamar', 'Sewa_kamar_controller::save');
$routes->post('save-pelunasan', 'Sewa_kamar_controller::save_pelunasan');
$routes->post('checkout', 'Sewa_kamar_controller::checkout');
$routes->get('cetak-struk/(:any)', 'Sewa_kamar_controller::cetak_struk/$1');
$routes->get('getBukti/(:any)', 'Sewa_kamar_controller::get_bukti_pembayaran/$1');


$routes->get('Front-Dashboard', 'Front_controller::index');
$routes->get('sewa-kamar-penyewa', 'Front_controller::sewa_kamar');
$routes->post('save-sewa-kamar-penyewa', 'Front_controller::save_sewa_kamar');
$routes->post('save-pembayaran', 'Front_controller::save_pembayaran');
$routes->post('delete-sewa-kamar', 'Front_controller::delete_sewa_kamar');
$routes->get('cetak-struk-dp/(:any)', 'Front_controller::cetak_struk_dp/$1');

$routes->get('Laporan', 'Laporan_controller::index');
$routes->post('laporan-penyewa', 'Laporan_controller::laporan_penyewa');
$routes->post('laporan-kamar', 'Laporan_controller::laporan_kamar');
$routes->post('laporan-penyewaan-kamar', 'Laporan_controller::laporan_penyewaan_kamar');
$routes->post('laporan-pembayaran', 'Laporan_controller::laporan_pembayaran');
