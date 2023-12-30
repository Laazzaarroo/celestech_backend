<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/getData', 'MainController::getData');
$routes->get('/getCctvList', 'MainController::getCctvList');
$routes->get('/unlistProduct', 'MainController::unlistProduct');
$routes->post('/add_product', 'MainController::add_product');
