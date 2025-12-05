<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->get('/cookies/accept', 'Cookies::accept');
$routes->get('/cookies/decline', 'Cookies::decline');

$routes->post('/auth/login', 'Login::login');
$routes->post('/auth/register', 'Register::register');

service('auth')->routes($routes);
