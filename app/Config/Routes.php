<?php
use CodeIgniter\Router\RouteCollection;
use App\Controllers\CVController;
use App\Controllers\AuthController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/cv/dashboard', [CVController::class, 'dashboard']);
$routes->get('/cv/upload', [CVController::class, 'upload']);
$routes->post('/cv/upload', [CVController::class, 'upload']);
$routes->get('/auth/login', [AuthController::class, 'login']);
$routes->post('/auth/login', [AuthController::class, 'login']);
$routes->get('/auth/register', [AuthController::class, 'register']);
$routes->post('/auth/register', [AuthController::class, 'register']);
$routes->get('/auth/profile', [AuthController::class, 'profile']);
$routes->get('/admin/dashboard', [CVController::class, 'adminDashboard']);
$routes->get('/cv/protectedView/(:num)', [CVController::class, 'protectedView']);
$routes->get('/auth/logout', 'AuthController::logout');