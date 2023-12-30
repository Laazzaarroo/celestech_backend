<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
 $routes->get('/', 'MainController::index');
 $routes->get('/getData', 'MainController::getData');
 $routes->get('/getCctvList', 'MainController::getCctvList');
 $routes->post('/unlistProduct', 'MainController::unlistProduct');
 $routes->post('/add_product', 'MainController::add_product');
 $routes->post('/add_brand', 'MainController::add_brand');
 $routes->get('/getTicketList', 'MainController::getTicketList');
 $routes->post('/change_status', 'MainController::change_status');
 $routes->match(['get', 'post'], '/r_status', 'MainController::r_status');
 $routes->match(['get', 'post'], '/sendReport', 'MainController::sendReport');
 $routes->get('/getClientList', 'MainController::getClientList');
 $routes->post('/getClientPurchase', 'MainController::getClientPurchase');
 $routes->post('/signIn', 'MainController::signIn');
 $routes->get('/getProductList', 'MainController::getProductList');
 $routes->post('/getProductData', 'MainController::getProductData');
 $routes->post('/verifyEmail', 'MainController::verifyEmail');
 $routes->post('/save_edit', 'MainController::save_edit');
 $routes->post('/delete_brand', 'MainController::delete_brand');
 $routes->post('/restore_prod', 'MainController::restore_prod');
 $routes->post('/signup', 'MainController::signup');
 $routes->post('/send_otp', 'MainController::send_otp');
 $routes->post('/getAccount', 'MainController::getAccount');
  $routes->post('/api/test', 'MainController::form_insert');
