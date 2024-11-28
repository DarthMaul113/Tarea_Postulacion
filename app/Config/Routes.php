<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas principales
$routes->get('/', 'IndicadoresController::index');
$routes->get('/historial-uf', 'HistorialUfController::index');
$routes->get('/indicadores', 'IndicadoresController::index');

// Rutas específicas Historial UF
$routes->get('/historial-uf/get', 'HistorialUfController::get');
$routes->get('/historial-uf/get/(:num)', 'HistorialUfController::getById/$1');
$routes->post('/historial-uf/create', 'HistorialUfController::create');
$routes->post('/historial-uf/update/(:num)', 'HistorialUfController::update/$1');
$routes->get('/historial-uf/edit/(:num)', 'HistorialUfController::edit/$1');
$routes->delete('/historial-uf/delete/(:num)', 'HistorialUfController::delete/$1');
$routes->get('/historial-uf/sync', 'HistorialUfController::sync');
