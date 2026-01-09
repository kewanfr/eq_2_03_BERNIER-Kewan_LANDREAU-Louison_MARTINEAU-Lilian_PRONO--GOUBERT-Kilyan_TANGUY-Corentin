<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// === Authentification ===
$routes->get('/login', 'Login::loginView');
$routes->get('/register', 'Register::registerView');
$routes->get('/logout', 'Login::logoutAction');
$routes->get('/auth/logout', 'Login::logoutAction');
$routes->post('/auth/login', 'Login::loginAction');
$routes->post('/auth/register', 'Register::registerAction');

// === Cookies ===
$routes->get('/cookies/accept', 'Cookies::accept');
$routes->get('/cookies/decline', 'Cookies::decline');

// === Produits ===
$routes->get('/products', 'Products::index'); // Page catalogue publique
$routes->get('/product/add', 'ProductController::add');
$routes->get('/product/purchase', 'ProductController::purchase');
$routes->post('/product/add/add', 'ProductController::addAction');

// === Panier ===
$routes->get('/cart', 'CartController::index');
$routes->post('/cart/add', 'CartController::add');
$routes->get('/cart/remove/(:num)', 'CartController::remove/$1');
$routes->post('/cart/update', 'CartController::update');
$routes->get('/cart/clear', 'CartController::clear');
$routes->get('/cart/count', 'CartController::count');

// === Commandes ===
$routes->get('/orders', 'OrderController::index');
$routes->get('/orders/(:num)', 'OrderController::details/$1');
$routes->get('/checkout', 'OrderController::checkout');
$routes->post('/order/place', 'OrderController::place');

// === Back Office (Admin) ===
$routes->group('admin', ['filter' => 'role:admin,commercial,preparation,production,saisonnier'], function($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::index');
    
    // Gestion des produits
    $routes->get('products', 'AdminController::products');
    $routes->get('products/create', 'AdminController::createProduct');
    $routes->get('products/edit/(:num)', 'AdminController::editProduct/$1');
    $routes->post('products/update/(:num)', 'AdminController::updateProduct/$1');
    $routes->get('products/delete/(:num)', 'AdminController::deleteProduct/$1');
    
    // Gestion des commandes
    $routes->get('orders', 'AdminController::orders');
    $routes->get('orders/(:num)', 'AdminController::orderDetails/$1');
    $routes->post('orders/(:num)/status', 'AdminController::updateOrderStatus/$1');
    $routes->get('orders/(:num)/cancel', 'AdminController::cancelOrder/$1');
    $routes->post('orders/(:num)/cancel', 'AdminController::cancelOrder/$1');
    
    // Gestion des utilisateurs (admin uniquement)
    $routes->get('users', 'AdminController::users', ['filter' => 'role:admin']);
    $routes->get('users/(:num)/roles', 'AdminController::editUserRoles/$1', ['filter' => 'role:admin']);
    $routes->post('users/(:num)/roles', 'AdminController::updateUserRoles/$1', ['filter' => 'role:admin']);
    
    // Gestion des stocks
    $routes->get('stock', 'AdminController::stock');
    $routes->post('stock/(:num)/adjust', 'AdminController::adjustStock/$1');
});

service('auth')->routes($routes);
